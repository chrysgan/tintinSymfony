SELECT table_name
FROM information_schema.tables
WHERE table_schema = 'tintin';

CREATE TABLE tintinSymfony.objets_personnages LIKE tintin.objets_personnages;
INSERT INTO tintinSymfony.objets_personnages SELECT * FROM tintin.objets_personnages;

select version()