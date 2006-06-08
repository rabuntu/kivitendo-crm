-- $Id: update110-130.sql 2006-06-03 10:40:42Z hlindemann $
CREATE TABLE  contmasch(
	mid integer,
	cid integer);
	
CREATE TABLE history (
	mid integer,
	datum date,
	art character varying(20),
	beschreibung text);
	
CREATE TABLE repauftrag (
	aid integer,
	mid integer,
	cause text,
	schaden text,
	reparatur text,
	bearbdate timestamp without time zone,
	employee integer,
	bearbeiter integer,
	anlagedatum timestamp without time zone,
	status integer,
	kdnr integer,
        counter bigint);
	
CREATE TABLE  maschmat (
	mid integer,
	aid integer,
	parts_id integer,
	betrag numeric(15,5),
	menge numeric(10,3));
	
CREATE TABLE contract (
	cid integer DEFAULT nextval('id'::text),
	contractnumber text,
	template text,
	bemerkung text,
	customer_id integer,
	anfangdatum date,
	betrag numeric(15,5),
	endedatum date );
	
CREATE TABLE maschine (
	id integer DEFAULT nextval('id'::text),
	parts_id integer,
	serialnumber text,
	standort text,
        inspdatum DATE,
        counter BIGINT);

CREATE TABLE wissencategorie(
	id integer DEFAULT nextval('id'::text) NOT NULL,
	name character varying(60),
	hauptgruppe integer
);

CREATE TABLE leads(
	id integer DEFAULT nextval('id'::text) NOT NULL,
	lead character varying(50)
);

CREATE TABLE wissencontent(
	id integer DEFAULT nextval('id'::text) NOT NULL,
	initdate timestamp without time zone NOT NULL,
	content text,
	employee integer,
	version integer,
	categorie integer
);

CREATE TABLE opportunity(
	id integer DEFAULT nextval('id'::text) NOT NULL,
	fid integer,
	title character varying(100),
	betrag numeric (15,5),
	zieldatum date,
	chance integer,
	status integer,
	notiz text,
	itime timestamp DEFAULT now(),
	mtime timestamp,
	iemployee integer,
	memployee integer
);

create table postit (
	id integer DEFAULT nextval('id'::text) NOT NULL,
	cause character varying(100),
	notes text,
	employee integer,
	date timestamp without time zone NOT NULL
);

CREATE TABLE tempcsvdata (
	uid  integer,
	csvdaten text
);
	
ALTER TABLE employee ADD COLUMN status integer;
ALTER TABLE employee ADD COLUMN termbegin integer;
ALTER TABLE employee ADD COLUMN termend integer;
ALTER TABLE defaults ADD COLUMN contnumber text;
ALTER TABLE customer ADD COLUMN lead integer;
ALTER TABLE customer ADD COLUMN leadsrc character varying(15);
ALTER TABLE custmsg ADD COLUMN akt boolean;
ALTER TABLE employee ADD COLUMN kdview integer;
ALTER TABLE employee alter COLUMN kdview SET DEFAULT 1;
ALTER TABLE customer ADD COLUMN sonder int;
ALTER TABLE vendor ADD COLUMN sonder int;

CREATE INDEX mid_key ON contmasch USING btree (mid);

UPDATE defaults SET contnumber=1000;
UPDATE employee SET kdview = 1;

INSERT INTO crm (uid,datum,version) VALUES (0,now(),'1.3.0');