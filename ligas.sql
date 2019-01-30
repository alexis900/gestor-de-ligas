drop schema if exists ligas;

create schema if not exists ligas default character set utf8;
use ligas;

create table liga (
  id int primary key auto_increment,
  nombre varchar(100) not null,
  fecha_creacion date null default null
);

create table equipo (
  id int primary key auto_increment,
  liga_id int not null,
  nombre varchar(150) not null, 

  constraint fk_equipo_liga
    foreign key (liga_id)
    references liga (id)
    on delete no action
    on update cascade
);

create table partido (
  id int primary key auto_increment,
  liga_id int not null,
  ronda_num int not null,
  equipo1_id int not null,
  equipo2_id int not null,
  puntos1 int null,
  puntos2 int null,
  fecha datetime null default null,
  
  constraint fk_partido_equipo1
    foreign key (equipo1_id)
    references equipo (id)
    on delete no action 
    on update no action,

  constraint fk_partido_equipo2
    foreign key (equipo2_id)
    references equipo (id)
    on delete no action
    on update no action,

  constraint fk_partido_liga1
    foreign key (liga_id)
    references liga (id)
    on delete no action
    on update no action
);

insert into liga(id,nombre,fecha_creacion) values 
(1, 'Campeonato 4 Barrios 2018','2018-03-21'),
(2, 'Liga Verano 2018','2018-07-11');

insert into equipo(id,liga_id,nombre) values
(1,1,'Barrio Norte'),
(2,1,'Barrio Sur'),
(3,1,'Barrio Este'),
(4,1,'Barrio Oeste'),

(5,2,'Playeros CF'),
(6,2,'Peña del Chiringuito'),
(7,2,'Birras Frescas'),
(8,2,'FC Chipirones'),
(9,2,'Peña Poca Broma');

insert into partido(id,liga_id,ronda_num,equipo1_id,equipo2_id,fecha,puntos1,puntos2) values 
(1,1,1,1,4,'2018-03-26 18:00',1,2),
(2,1,1,2,3,'2018-03-27 19:00',3,2),
(3,1,2,4,3,'2018-04-03 18:00',2,2),
(4,1,2,1,2,'2018-04-03 20:00',5,2),
(5,1,3,2,4,'2018-04-10 18:00',1,0),
(6,1,3,3,1,'2018-04-11 18:00',null,null),

(7,2,1,6,9,'2018-07-15 18:00',1,2),
(8,2,1,7,8,'2018-07-15 20:00',3,0),
(9,2,2,9,7,'2018-07-23 19:00',4,1),
(10,2,2,5,6,'2018-07-24 19:00',1,0),
(11,2,3,7,5,'2018-07-30 19:00',1,3),
(12,2,3,8,9,'2018-07-31 19:00',null,null),
(13,2,4,5,8,'2018-08-06 18:00',null,null),
(14,2,4,6,7,'2018-08-06 20:00',null,null),
(15,2,5,8,6,null,null,null),
(16,2,5,9,5,null,null,null);

-- TABLA DE USUARIOS ADMINISTRADORES

create table administradores (
  login varchar(50) not null primary key,
  password varchar(100) not null
);

insert into administradores values
('admin1',md5('123456')),
('admin2',md5('qazwsx'));
  

-- USUARIO DE LA BASE DE DATOS

drop user if exists 'ligas'@'localhost';
create user 'ligas'@'localhost' identified by '123qweASD!';
grant select,insert,delete,update on ligas.* to 'ligas'@'localhost';


