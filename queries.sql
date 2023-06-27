--Query
SELECT id_post
FROM post
WHERE to_tsvector('english', description) @@ plainto_tsquery('english', 'leg');

-- TO SHOW THE TABLES CREATED

-- SELECT * FROM account;

-- SELECT * FROM group_table;

SELECT * FROM post;