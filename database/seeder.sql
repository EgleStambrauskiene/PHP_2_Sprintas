-- utf8
SET NAMES utf8;

-- Turn off foreign key check
SET FOREIGN_KEY_CHECKS=0;

-- Seeding table departments
DELETE FROM staff.departments;
INSERT INTO staff.departments (`title`) VALUES
('Administracija'),
('Finansų tarnyba'),
('Pardavimų skyrius'),
('Vadovybė');

-- Seeding table projects
DELETE FROM staff.projects;
INSERT INTO staff.projects (`title`, `budget`, `description`) VALUES
('ES projektas "Vaistai"', 10000.00, 'Skirtas vaistams'),
('ES projektas "Maistas"', 12000.00, 'Skirtas maistui'),
('ES projektas "Vanduo"', 14000.00, 'Skirtas vandeniui'),
('ES projektas "Oras"' , 11320.00, 'Skirtas orui'),
('Projektas "Laisvamanis"' , 1312.00, 'Skirtas geriems žmonėms remti');

-- Seeding table persons
DELETE FROM staff.persons;
INSERT INTO staff.persons (`name`, `lastname`, `department_id`) VALUES
('Karolis', 'Didysis', 4),
('Hermiona', 'Įkyrėlė', 2),
('Haris', 'Poteris', 3),
('Albas', 'Dumbldoras', 1),
('Drakas', 'Smirdžius', 3),
('Laisvasis', 'Šaulys', NULL);


-- Seeding table persons_projects
DELETE FROM staff.persons_projects;
INSERT INTO staff.persons_projects (`person_id`, `project_id`) VALUES
-- Karolis, Vaistai
(1,1),
-- Hermiona, Vaistai
(2,1),
-- Hermiona, Maistas
(2,2),
-- Hermiona, Vanduo
(2,3),
-- Hermiona, Oras
(2,4),
-- Haris, Vaistai
(3,1),
-- Haris, Maistas
(3,2),
-- Albas, Oras
(4,4),
-- Šaulys, Laisvamanis
(6,5);

-- Turn on foreign key check
SET FOREIGN_KEY_CHECKS=1;

