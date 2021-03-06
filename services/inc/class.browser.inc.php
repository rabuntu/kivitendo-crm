<?php
    /**************************************************************************\
    * This file was originaly written by Jan Dierolf (jadi75@gmx.de)           *
    * ------------------------------------------------------------------------ *
    *  This program is free software; you can redistribute it and/or modify it *
    *  under the terms of the GNU General Public License as published by the   *
    *  Free Software Foundation; either version 2 of the License, or (at your  *
    *  option) any later version.                                              *
    \**************************************************************************/

  /* $Id: class.browser.inc.php 15134 2004-05-05 12:06:13Z reinerj $ */

    class browser
    {
        var $BROWSER_AGENT;
        var $BROWSER_VER;
        var $BROWSER_PLATFORM;
        var $br;
        var $p;
        var $data;

        function browser()
        {
            $HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
            /*
                Determine browser and version
            */
            if(ereg('MSIE ([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$log_version))
            {
                $this->BROWSER_VER = $log_version[1];
                $this->BROWSER_AGENT = 'IE';
            }
            elseif(ereg('Opera ([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$log_version) ||
                ereg('Opera/([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$log_version))
            {
                $this->BROWSER_VER   = $log_version[1];
                $this->BROWSER_AGENT = 'OPERA';
            }
            elseif(eregi('iCab ([0-9].[0-9a-zA-Z]{1,4})',$HTTP_USER_AGENT,$log_version) ||
                eregi('iCab/([0-9].[0-9a-zA-Z]{1,4})',$HTTP_USER_AGENT,$log_version))
            {
                $this->BROWSER_VER   = $log_version[1];
                $this->BROWSER_AGENT = 'iCab';
            } 
            elseif(ereg('Gecko',$HTTP_USER_AGENT,$log_version))
            {
                $this->BROWSER_VER   = $log_version[1];
                $this->BROWSER_AGENT = 'MOZILLA';
            }
            elseif(ereg('Konqueror/([0-9].[0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$log_version) ||
                ereg('Konqueror/([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$log_version))
            {
                $this->BROWSER_VER=$log_version[1];
                $this->BROWSER_AGENT='Konqueror';
            }
            else
            {
                $this->BROWSER_VER=0;
                $this->BROWSER_AGENT='OTHER';
            }

            /*
                Determine platform
            */
            if(strstr($HTTP_USER_AGENT,'Win'))
            {
                $this->BROWSER_PLATFORM='Win';
            }
            elseif(strstr($HTTP_USER_AGENT,'Mac'))
            {
                $this->BROWSER_PLATFORM='Mac';
            }
            elseif(strstr($HTTP_USER_AGENT,'Linux'))
            {
                $this->BROWSER_PLATFORM='Linux';
            }
            elseif(strstr($HTTP_USER_AGENT,'Unix'))
            {
                $this->BROWSER_PLATFORM='Unix';
            }
            elseif(strstr($HTTP_USER_AGENT,'Beos'))
            {
                $this->BROWSER_PLATFORM='Beos';
            }
            else
            {
                $this->BROWSER_PLATFORM='Other';
            }

            /*
            echo "\n\nAgent: $HTTP_USER_AGENT";
            echo "\nIE: ".browser_is_ie();
            echo "\nMac: ".browser_is_mac();
            echo "\nWindows: ".browser_is_windows();
            echo "\nPlatform: ".browser_get_platform();
            echo "\nVersion: ".browser_get_version();
            echo "\nAgent: ".browser_get_agent();
            */

            // The br and p functions are supposed to return the correct
            // value for tags that do not need to be closed.  This is
            // per the xhmtl spec, so we need to fix this to include
            // all compliant browsers we know of.
            if($this->BROWSER_AGENT == 'IE')
            {
                $this->br = '<br/>';
                $this->p = '<p/>';
            }
            else
            {
                $this->br = '<br>';
                $this->p = '<p>';
            }
        }

        function return_array()
        {
            $this->data = array(
                'agent'    => $this->get_agent(),
                'version'  => $this->get_version(),
                'platform' => $this->get_platform()
            );

            return $this->data;
        }

        function get_agent()
        {
            return $this->BROWSER_AGENT;
        }

        function get_version()
        {
            return $this->BROWSER_VER;
        }

        function get_platform()
        {
            return $this->BROWSER_PLATFORM;
        }

        function is_linux()
        {
            if($this->get_platform()=='Linux')
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        function is_unix()
        {
            if($this->get_platform()=='Unix')
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        function is_beos()
        {
            if($this->get_platform()=='Beos')
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        function is_mac()
        {
            if($this->get_platform()=='Mac')
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        function is_windows()
        {
            if($this->get_platform()=='Win')
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        function is_ie()
        {
            if($this->get_agent()=='IE')
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        function is_netscape()
        {
            if($this->get_agent()=='MOZILLA')
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        function is_opera()
        {
            if($this->get_agent()=='OPERA')
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        // Echo content headers for file downloads
        function content_header($fn='',$mime='',$length='',$nocache=True)
        {
            // if no mime-type is given or it's the default binary-type, guess it from the extension
            if(empty($mime) || $mime == 'application/octet-stream')
            {
                $mime_magic = createObject('phpgwapi.mime_magic');
                $mime = $mime_magic->filename2mime($fn);
            }
            if($fn)
            {
                if($this->get_agent() == 'IE') // && browser_get_version() == "5.5")
                {
                    $attachment = '';
                }
                else
                {
                    $attachment = ' attachment;';
                }

                // Show this for all
                header('Content-disposition:'.$attachment.' filename="'.$fn.'"');
                header('Content-type: '.$mime);

                if($length)
                {
                    header('Content-length: '.$length);
                }

                if($nocache)
                {
                    header('Pragma: no-cache');
                    header('Pragma: public');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                }
            }
        }
    }
?>
