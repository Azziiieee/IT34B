CREATE TABLE user_sessions(
    -- Session id to be used in dashboards and REFERENCES
    session_id INT AUTO_INCREMENT PRIMARY KEY,

    -- User ID references
    user_id NOT NULL,

    -- Session attributes
    session_start DATETIME NOT NULL DEFAULT_CURRENT TIME,
    session_end DATETIME DEFAULT NULL,
    session_duration INT DEFAULT NULL,

    -- Constraints and Foreign Key Implementation
    CONSTRAINT fk_user_sessions_user_id
        FOREIGN KEY (user_id)
        REFERENCES users(user_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE 
);