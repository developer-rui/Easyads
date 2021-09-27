

CREATE TABLE IF NOT EXISTS users (
    id VARCHAR(255) NOT NULL,
    email VARCHAR(70) NOT NULL,
    username VARCHAR(50) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    registration_date DATETIME,         

    PRIMARY KEY (id),
    UNIQUE (email(5)),
    UNIQUE (username(5))        
);



CREATE TABLE IF NOT EXISTS remembered_users (
    id VARCHAR(255) NOT NULL,
    cookie_hash VARCHAR(255) NOT NULL,
    user_id VARCHAR(255) NOT NULL,
    creation_date DATETIME, 

    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS ads (
    id VARCHAR(255) NOT NULL,
    title TEXT NOT NULL,
    text TEXT NOT NULL,
    category VARCHAR(30) NOT NULL DEFAULT 'other',
    post_date DATETIME NOT NULL,
    premium TINYINT UNSIGNED NOT NULL DEFAULT 0,
    user_id VARCHAR(255) NOT NULL,

    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS favorites (
    ad_id VARCHAR(255) NOT NULL,
    user_id VARCHAR(255) NOT NULL,        

    FOREIGN KEY (ad_id) REFERENCES ads(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

  
    