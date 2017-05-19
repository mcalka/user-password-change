CREATE TABLE tbl_user (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(128) NOT NULL,
    password VARCHAR(128) NOT NULL,
    email VARCHAR(128) NOT NULL
);

INSERT INTO tbl_user (username, password, email) VALUES ('test', '$2y$13$VaD7usC.UlLj7JcOr1R/EuHNCZHE5PlWMY99z1NhW0Bdyb0ZeaoC6', 'test1@example.com');
