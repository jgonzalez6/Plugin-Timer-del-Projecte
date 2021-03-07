#SCRIPT CREACIÓ BASE DE DADES PER PLUGIN DEL COMPTADOR DE TEMPS

DROP DATABASE IF EXISTS timer;
CREATE DATABASE IF NOT EXISTS timer;
CREATE USER IF NOT EXISTS 'user'@'localhost' IDENTIFIED BY 'aplicacions';
GRANT ALL PRIVILEGES ON timer . * TO 'user'@'localhost';
FLUSH PRIVILEGES;

USE timer;
CREATE TABLE IF NOT EXISTS comptador (
  Codi                INT UNSIGNED,
  Temps_Inicial       DATETIME,
  Temps_Final         DATETIME,
  Temps_Transcorregut SMALLINT,
  
  
  CONSTRAINT PK_COMPTADOR PRIMARY KEY (Codi)
);

#TEST DE CONSULTES EMPRADES AL PHP

#Inserir temps actual al premer botó Inici
INSERT INTO comptador (Codi,Temps_Inicial)
                  VALUES (Codi,curtime());


#Inserir temps final i mantenir l'inicial al premer stop (una subconsulta)
UPDATE comptador SET Temps_Final = curtime() WHERE Codi = (SELECT COUNT(*) FROM comptador);


#Actualitzar la columna Temps Transcorregut per saber la diferència de temps de la sessió (subconsulta doble)
UPDATE comptador SET Temps_Transcorregut = (SELECT TIMEDIFF(Temps_Final, Temps_Inicial) FROM comptador WHERE Codi = (SELECT COUNT(*) FROM comptador)) WHERE Codi = (Select count(*) FROM comptador);
Select count(*) FROM comptador;

#Funció per transformar diferents mesures de temps
SELECT FORMAT(SUM(Temps_Transcorregut/60),2) FROM comptador;