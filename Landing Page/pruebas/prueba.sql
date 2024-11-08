CREATE DATABASE pruebaProyecto;
use pruebaProyecto;
CREATE TABLE users (
    id_users int PRIMARY Key AUTO_INCREMENT,
    user_name VARCHAR(20) not null,
    user_password VARCHAR(50) not null
)


INSERT INTO users (user_name, user_password) VALUE 
('santiPrah12', SHA1('elmejor1234')),
('marianaNoob', SHA1('pobr3t0na')),
('gamyGod', SHA1('JOJO'));