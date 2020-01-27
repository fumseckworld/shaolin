DROP TABLE IF EXISTS users;

CREATE TABLE users (
id INTEGER PRIMARY KEY AUTO_INCREMENT,
name varchar(255) default NULL,
email varchar(255) default NULL,
phone varchar(100) default NULL,
created_at varchar(255)
);

INSERT INTO users (id,name,email,phone,created_at) VALUES (1,'Tad Hyde','lectus@Quisque.net','1-749-692-7311','May 15th, 2019'),(2,'Amanda Mann','arcu@Phasellusornare.ca','1-835-560-4890','August 1st, 2019'),(3,'Kellie Barnes','pellentesque@urnaUttincidunt.org','637-0583','March 8th, 2019'),(4,'Ocean Yates','Donec.non.justo@ligulaDonec.org','1-125-654-5189','July 30th, 2020'),(5,'Wynter Dickson','ridiculus.mus@inlobortistellus.net','1-121-452-2534','June 19th, 2020'),(6,'Ethan M. Welch','purus.Nullam@erat.ca','1-536-339-0064','April 4th, 2020'),(7,'Keiko Chen','aliquet.odio@aliquamadipiscing.co.uk','583-4487','July 28th, 2019'),(8,'Valentine I. Ross','tristique.pellentesque@ametlorem.co.uk','1-613-633-6986','December 27th, 2018'),(9,'Pascale H. Dillon','quis.diam@risusQuisque.com','167-7479','November 20th, 2019'),(10,'Shaeleigh Bonner','Donec.tempus@lectusNullamsuscipit.co.uk','1-970-673-9794','March 13th, 2019');
INSERT INTO users (id,name,email,phone,created_at) VALUES (11,'Myra Page','Proin.vel.nisl@euismodmauriseu.edu','286-7499','February 5th, 2019'),(12,'Leslie X. Cain','purus@Nullasempertellus.com','1-944-304-5677','January 28th, 2019'),(13,'Evelyn I. Tran','malesuada.fringilla@sollicitudincommodoipsum.co.uk','332-1095','January 4th, 2020'),(14,'Alika P. Workman','cursus.in.hendrerit@Integertincidunt.ca','746-4216','December 14th, 2020'),(15,'Uma L. Dejesus','parturient@neque.org','1-594-861-3514','November 22nd, 2019'),(16,'Sandra O. Alston','sit.amet.orci@Quisqueaclibero.ca','1-749-636-3109','July 15th, 2019'),(17,'Linus V. Olsen','ac@Nulla.co.uk','1-802-331-8961','September 30th, 2020'),(18,'Cameron Allison','ut@sem.com','1-825-548-3021','January 20th, 2019'),(19,'Simone Lara','lacinia.mattis.Integer@estacfacilisis.net','793-4451','October 31st, 2020'),(20,'Benjamin Snider','risus.Donec.egestas@risusa.net','1-101-889-5317','May 7th, 2019');
INSERT INTO users (id,name,email,phone,created_at) VALUES (21,'Vincent X. Mccall','Cum.sociis.natoque@ornarelectusjusto.com','1-182-480-2219','January 16th, 2019'),(22,'Keith Conway','massa@euismodacfermentum.edu','1-448-601-1826','November 10th, 2019'),(23,'Dahlia C. James','lorem@AliquamnislNulla.net','1-670-923-5533','March 5th, 2020'),(24,'Jeremy P. Glass','convallis.convallis@sedsem.ca','690-4370','September 26th, 2020'),(25,'Wyoming Mccormick','urna.suscipit@Fuscediamnunc.ca','208-3361','December 24th, 2019'),(26,'Edan Booth','convallis@sedconsequatauctor.net','741-1066','January 20th, 2019'),(27,'Lenore Galloway','Curae.Phasellus@nisi.ca','1-632-841-1479','December 23rd, 2019'),(28,'Dane M. Avery','cursus@Quisqueimperdiet.org','1-327-357-0075','January 12th, 2019'),(29,'Maggie Yang','sed.facilisis.vitae@sitamet.ca','504-4649','July 10th, 2019'),(30,'Jamal G. Thomas','Phasellus.vitae@disparturient.edu','639-3859','July 3rd, 2020');
INSERT INTO users (id,name,email,phone,created_at) VALUES (31,'Zenia Dale','ultrices.mauris.ipsum@quisdiam.co.uk','1-432-312-5212','July 23rd, 2020'),(32,'Travis Kemp','Cum.sociis.natoque@atiaculisquis.com','1-351-723-7950','June 18th, 2020'),(33,'Lucian S. Byers','consectetuer.cursus@IntegermollisInteger.co.uk','339-9267','June 28th, 2019'),(34,'Claire Leonard','ac@Maecenasornareegestas.ca','1-540-742-7368','September 6th, 2019'),(35,'Ashton Jordan','neque.venenatis.lacus@aliquet.ca','383-4178','May 25th, 2020'),(36,'Orli Roy','condimentum.Donec@nec.com','908-3019','February 12th, 2019'),(37,'Cleo Y. Santana','et.ipsum.cursus@imperdietdictum.com','564-8287','March 28th, 2020'),(38,'Aquila Mcknight','ut.quam.vel@ante.edu','1-703-519-8680','September 2nd, 2020'),(39,'Keegan Z. Guzman','mollis.vitae.posuere@aliquamiaculis.co.uk','764-5438','June 30th, 2019'),(40,'Summer L. Whitfield','magna.a@necmetusfacilisis.org','416-0075','February 4th, 2020');
INSERT INTO users (id,name,email,phone,created_at) VALUES (41,'Alma Vaughn','Cras.pellentesque@Aliquamvulputate.org','1-639-658-6193','August 8th, 2020'),(42,'Wayne X. Hebert','natoque.penatibus.et@lectusconvallis.org','1-850-930-6834','September 2nd, 2019'),(43,'Zeus T. Davidson','aliquam.arcu@ante.ca','1-174-180-8365','February 18th, 2020'),(44,'Abigail Walters','Aenean.eget.magna@gravidamaurisut.net','1-107-401-4418','June 25th, 2020'),(45,'Shelly Goodman','id.libero@etmagnaPraesent.org','1-170-684-4016','June 18th, 2020'),(46,'Mercedes Combs','velit.Quisque@anteVivamusnon.ca','943-0308','March 10th, 2020'),(47,'Aline R. Espinoza','elit.fermentum@lobortis.com','1-649-746-6026','December 23rd, 2020'),(48,'Nicole X. Compton','In@Maurisquis.com','1-637-176-4660','July 1st, 2020'),(49,'Illiana A. Crawford','quis@veliteu.com','1-675-540-9169','April 27th, 2019'),(50,'Mohammad R. Rosales','luctus.aliquet.odio@libero.ca','249-3066','December 6th, 2019');
INSERT INTO users (id,name,email,phone,created_at) VALUES (51,'Micah I. Bond','euismod.est@aliquet.com','769-2372','January 17th, 2020'),(52,'Brady Q. Bradford','nunc.sit@interdumNunc.co.uk','499-5924','October 16th, 2019'),(53,'Alana Graham','fringilla.cursus.purus@Seddictum.org','1-808-190-8108','August 15th, 2019'),(54,'Shad Barrett','aliquet.odio.Etiam@sagittis.net','1-779-687-6390','September 5th, 2020'),(55,'Victor Obrien','dui.quis.accumsan@duinec.net','1-106-851-7254','September 20th, 2019'),(56,'Nathaniel R. Walters','elit.sed.consequat@placeratorcilacus.org','394-8135','September 21st, 2019'),(57,'Angelica Collins','Nullam@dignissimtempor.edu','1-838-594-8113','November 24th, 2020'),(58,'Hop J. Ayers','Cras@orci.co.uk','590-6519','January 3rd, 2020'),(59,'Lani A. Craig','quam.a@augueutlacus.net','1-469-916-1579','November 18th, 2020'),(60,'Maya E. House','scelerisque.mollis.Phasellus@nulla.org','777-2913','May 1st, 2020');
INSERT INTO users (id,name,email,phone,created_at) VALUES (61,'Howard Z. Clayton','Phasellus@idmollisnec.ca','630-5487','November 17th, 2019'),(62,'Travis Day','metus@NullainterdumCurabitur.com','1-548-283-4986','April 26th, 2020'),(63,'Maisie Cooley','congue@blanditat.org','830-4360','February 27th, 2019'),(64,'Price Graves','nonummy@tellusjustosit.com','715-6777','February 6th, 2020'),(65,'Conan F. Mcneil','amet@posuerevulputate.net','149-1175','September 30th, 2019'),(66,'Levi J. Horton','lectus.quis@a.com','1-181-611-4796','August 17th, 2019'),(67,'Lars C. Hancock','ac@tellusAeneanegestas.edu','982-5933','June 9th, 2019'),(68,'Naida Larson','dictum.cursus@loremauctorquis.edu','1-645-917-5242','May 20th, 2019'),(69,'Aurora J. Higgins','auctor.vitae.aliquet@nislNullaeu.org','832-2436','May 7th, 2019'),(70,'Maite Hyde','amet.lorem.semper@venenatis.edu','1-906-733-9036','May 28th, 2020');
INSERT INTO users (id,name,email,phone,created_at) VALUES (71,'Nelle Lucas','Mauris@Duisvolutpat.org','580-7872','June 12th, 2020'),(72,'Jemima M. Richardson','Suspendisse@posuerecubilia.edu','829-3306','October 22nd, 2019'),(73,'Oren V. Barlow','erat@eleifendvitaeerat.ca','715-5782','December 21st, 2020'),(74,'Carly O. Simon','eros@vehiculaetrutrum.org','526-3488','December 8th, 2019'),(75,'Tarik Michael','augue@malesuadavel.co.uk','394-7607','November 7th, 2019'),(76,'Maite Wiggins','dictum@Aeneanegestas.net','1-405-219-4553','August 14th, 2020'),(77,'Wing W. Jackson','ornare.egestas@quis.ca','543-8245','April 23rd, 2019'),(78,'MacKenzie Frost','ligula.eu@laciniavitaesodales.ca','349-2029','May 5th, 2020'),(79,'Arthur Holder','pede.Nunc@Quisqueimperdieterat.edu','778-3506','September 18th, 2020'),(80,'Jared Love','Cras@euligula.net','526-3601','March 1st, 2020');
INSERT INTO users (id,name,email,phone,created_at) VALUES (81,'Sonya B. Guerra','Etiam.ligula@loremsemperauctor.edu','506-9412','August 20th, 2020'),(82,'Kamal Ross','mauris.aliquam@veliteu.org','1-351-239-9184','April 23rd, 2019'),(83,'Urielle Burton','sem.elit.pharetra@dignissimpharetra.co.uk','813-8546','December 15th, 2020'),(84,'Emerson Thompson','convallis@temporbibendumDonec.net','1-777-239-8389','April 1st, 2020'),(85,'Desiree R. Sargent','dictum.Proin@ac.net','1-415-146-8097','February 10th, 2019'),(86,'Meghan Farrell','Sed.eu.nibh@pede.edu','1-442-552-8787','April 26th, 2019'),(87,'Dominique Baldwin','est.Nunc.laoreet@Nullaeget.ca','726-9110','October 19th, 2020'),(88,'Chantale F. Stevenson','massa.Vestibulum.accumsan@tincidunt.com','720-0467','September 24th, 2020'),(89,'Brenda Y. Bonner','turpis.In.condimentum@magnaatortor.org','1-810-511-0688','October 30th, 2019'),(90,'Amaya Douglas','Phasellus@hendreritid.co.uk','1-401-761-4983','April 26th, 2020');
INSERT INTO users (id,name,email,phone,created_at) VALUES (91,'Illiana Hogan','pharetra@per.co.uk','165-9760','October 29th, 2019'),(92,'Ezra U. Barber','commodo.auctor@dolorFuscemi.org','996-4503','August 16th, 2020'),(93,'Murphy O. Odonnell','odio.semper@lobortistellus.net','792-2937','March 6th, 2020'),(94,'Morgan Santiago','Nullam.lobortis@consequat.net','479-9581','June 18th, 2019'),(95,'Joelle A. Harper','metus.eu.erat@odioauctor.com','866-0806','July 13th, 2019'),(96,'Risa Mccormick','eget.laoreet@porttitorerosnec.net','684-7368','August 26th, 2020'),(97,'David N. Bell','molestie.pharetra.nibh@atliberoMorbi.com','1-962-117-5154','December 7th, 2020'),(98,'Melanie W. Barber','orci.luctus.et@ametconsectetueradipiscing.co.uk','1-259-993-5565','January 13th, 2019'),(99,'Chelsea Sellers','malesuada@Proin.org','1-450-589-2423','June 19th, 2019'),(100,'Jordan Riley','pede@magnaseddui.com','415-2763','May 26th, 2019');
