CREATE DATABASE pruebaProyecto;
use pruebaProyecto;
CREATE TABLE users (
    id_users int PRIMARY Key AUTO_INCREMENT,
    user_name VARCHAR(20) not null,
    user_password VARCHAR(50) not null
)


INSERT INTO users (user_name, user_password) VALUE 
('santiPrah12','elmejor1234'),
('marianaNoob', 'pobr3t0na'),
('gamyGod', 'JOJO');

SELECT id_users, user_name ,user_password FROM users where user_name = 'gamyGod';