/* Table pays */
INSERT INTO tintinSymfony.pays (	id,	code,	alpha2,	alpha3,	nom_fr,	nom_en	)
SELECT 
	case when paysid = 0 then 242 else paysid end ,
	code, alpha2, alpha3, nom_fr, nom_en
FROM tintin.pays ;

/* Table categorie  / type */
INSERT INTO tintinSymfony.categorie (	id,code,nom,image)
SELECT	typeid,	typecode, typelib, typeimg
from tintin.types;

/* Table personnage */
INSERT INTO tintinSymfony.personnage (	id,prenom,nom,alias,sexe,image, description)
select 	persid, persprenom, persnom, persalias,  perssexe,  persimg,  persdesc
from tintin.personnages;

/* Table editeur */
INSERT INTO tintinSymfony.editeur ( id,nom,pays_id,description,image,annee_creation,annee_fermeture,commentaire)
select 	edi.ediid, edi.edinom, pays.id, edi.edidesc, edi.ediimg, edi.edicreateyear, edi.edicloseyear, edi.commentaires
from tintin.editeurs edi
join tintinSymfony.pays pays on pays.alpha2 = edi.edipays;

/* Table serie */
INSERT INTO tintinSymfony.serie (	id,nom,annee,mois,description, commentaire)
SELECT	serid, sernom, serannee,sermois,serdesc,commentaires
from tintin.series;

/* Table objet */
INSERT INTO tintinSymfony.objet 
	(id,serie_id,nom,taille,description,reference,prix,editeur_id,categorie_id,annee,mois,created_at,updated_at,poids,
    rangement,montant_achat,est_possede,description_possession,actif)
SELECT	obj.objid,obj.serid,obj.objnom,obj.objtaille, obj.objdesc,obj.objref,obj.objprix,obj.ediid,obj.typeid,obj.objannee,obj.objmois,obj.objdatecreation,
		obj.objdateupdate,obj.objpoids,obj.rangement,obj.mt_achat,obj.possede,obj.detail_sur_possession,obj.objactif 
from tintin.objets obj;

/* Table Objet_personnage */
INSERT INTO tintinSymfony.objet_personnage (objet_id,personnage_id)
select 	objid , persid
from tintin.objets_persos;

/* Table Objet_image */
INSERT INTO tintinSymfony.objet_image (objet_id , image, ordre)
select 	oi.objid, oi.imgfile , oi.imgorder
from tintin.objets_images oi;

/* Table Users */
INSERT INTO tintinSymfony.user (email, alias, password, roles, newsletter, created_at )
select mbmail, mbusername, mbuserpass, '["ROLE_USER"]', case when mbnews = 'O' then true else false end , mbdate
from tintin.membres;
