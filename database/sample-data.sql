-- Sample data for E-Library System
-- Run this after importing library-db.sql

USE `library-db`;

-- Insert sample categories
INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Fiction'),
(2, 'Non-Fiction'),
(3, 'Science'),
(4, 'Technology'),
(5, 'History'),
(6, 'Biography'),
(7, 'Self-Help'),
(8, 'Mystery'),
(9, 'Romance'),
(10, 'Fantasy');

-- Insert sample authors
INSERT INTO `authors` (`id`, `name`) VALUES
(1, 'J.K. Rowling'),
(2, 'George Orwell'),
(3, 'Stephen King'),
(4, 'Agatha Christie'),
(5, 'Dan Brown'),
(6, 'Malcolm Gladwell'),
(7, 'Yuval Noah Harari'),
(8, 'Dale Carnegie'),
(9, 'James Clear'),
(10, 'Michelle Obama');

-- Insert sample books
INSERT INTO `books` (`title`, `author`, `description`, `price`, `image`, `category_id`, `author_id`, `type`) VALUES
('Harry Potter and the Philosopher\'s Stone', 'J.K. Rowling', 'Harry Potter has never even heard of Hogwarts when the letters start dropping on the doormat at number four, Privet Drive. Addressed in green ink on yellowish parchment with a purple seal, they are swiftly confiscated by his grisly aunt and uncle.', 19.99, 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1598823299i/42844155.jpg', 10, 1, 'both'),

('1984', 'George Orwell', 'Among the seminal texts of the 20th century, Nineteen Eighty-Four is a rare work that grows more haunting as its futuristic purgatory becomes more real. Published in 1949, the book offers political satirist George Orwell\'s nightmarish vision of a totalitarian, bureaucratic world.', 15.99, 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1657781256i/61439040.jpg', 1, 2, 'borrow'),

('The Shining', 'Stephen King', 'Jack Torrance\'s new job at the Overlook Hotel is the perfect chance for a fresh start. As the off-season caretaker at the atmospheric old hotel, he\'ll have plenty of time to spend reconnecting with his family and working on his writing.', 18.50, 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1353277730i/11588.jpg', 8, 3, 'both'),

('Murder on the Orient Express', 'Agatha Christie', 'Just after midnight, the famous Orient Express is stopped in its tracks by a snowdrift. By morning, the millionaire Samuel Edward Ratchett lies dead in his compartment, stabbed a dozen times, his door locked from the inside.', 14.99, 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1638634505i/853510.jpg', 8, 4, 'borrow'),

('The Da Vinci Code', 'Dan Brown', 'While in Paris, Harvard symbologist Robert Langdon is awakened by a phone call in the dead of the night. The elderly curator of the Louvre has been murdered inside the museum, his body covered in baffling symbols.', 16.99, 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1579126560i/968.jpg', 8, 5, 'sale'),

('Outliers: The Story of Success', 'Malcolm Gladwell', 'In this stunning new book, Malcolm Gladwell takes us on an intellectual journey through the world of "outliers"--the best and the brightest, the most famous and the most successful. He asks the question: what makes high-achievers different?', 21.99, 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1344266315i/3228917.jpg', 7, 6, 'both'),

('Sapiens: A Brief History of Humankind', 'Yuval Noah Harari', '100,000 years ago, at least six human species inhabited the earth. Today there is just one. Us. Homo sapiens. How did our species succeed in the battle for dominance? Why did our foraging ancestors come together to create cities and kingdoms?', 24.99, 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1703329310i/23692271.jpg', 5, 7, 'borrow'),

('How to Win Friends and Influence People', 'Dale Carnegie', 'You can go after the job you want...and get it! You can take the job you have...and improve it! You can take any situation you\'re in...and make it work for you! Since its release in 1936, How to Win Friends and Influence People has sold more than 30 million copies.', 12.99, 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1442726934i/4865.jpg', 7, 8, 'both'),

('Atomic Habits', 'James Clear', 'No matter your goals, Atomic Habits offers a proven framework for improving--every day. James Clear, one of the world\'s leading experts on habit formation, reveals practical strategies that will teach you exactly how to form good habits, break bad ones, and master the tiny behaviors that lead to remarkable results.', 22.99, 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1655988385i/40121378.jpg', 7, 9, 'sale'),

('Becoming', 'Michelle Obama', 'In a life filled with meaning and accomplishment, Michelle Obama has emerged as one of the most iconic and compelling women of our era. As First Lady of the United States of America, she helped create the most welcoming and inclusive White House in history.', 26.99, 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1528206996i/38746485.jpg', 6, 10, 'both'),

('The Great Gatsby', 'F. Scott Fitzgerald', 'The Great Gatsby, F. Scott Fitzgerald\'s third book, stands as the supreme achievement of his career. This exemplary novel of the Jazz Age has been acclaimed by generations of readers.', 13.99, 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1490528560i/4671.jpg', 1, NULL, 'borrow'),

('To Kill a Mockingbird', 'Harper Lee', 'The unforgettable novel of a childhood in a sleepy Southern town and the crisis of conscience that rocked it. To Kill a Mockingbird became both an instant bestseller and a critical success when it was first published in 1960.', 14.99, 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1553383690i/2657.jpg', 1, NULL, 'both'),

('The Hobbit', 'J.R.R. Tolkien', 'In a hole in the ground there lived a hobbit. Not a nasty, dirty, wet hole, filled with the ends of worms and an oozy smell, nor yet a dry, bare, sandy hole with nothing in it to sit down on or to eat: it was a hobbit-hole, and that means comfort.', 17.99, 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1546071216i/5907.jpg', 10, NULL, 'borrow'),

('The Catcher in the Rye', 'J.D. Salinger', 'The hero-narrator of The Catcher in the Rye is an ancient child of sixteen, a native New Yorker named Holden Caulfield. Through circumstances that tend to preclude adult, secondhand description, he leaves his prep school in Pennsylvania and goes underground in New York City for three days.', 13.50, 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1398034300i/5107.jpg', 1, NULL, 'both'),

('Think and Grow Rich', 'Napoleon Hill', 'Think and Grow Rich has been called the "Granddaddy of All Motivational Literature." It was the first book to boldly ask, "What makes a winner?" The man who asked and listened for the answer, Napoleon Hill, is now counted in the top ranks of the world\'s winners himself.', 11.99, 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1463241782i/30186948.jpg', 7, NULL, 'sale'),

('The 7 Habits of Highly Effective People', 'Stephen Covey', 'One of the most inspiring and impactful books ever written, The 7 Habits of Highly Effective People has captivated readers for 25 years. It has transformed the lives of Presidents and CEOs, educators and parents.', 19.99, 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1421842784i/36072.jpg', 7, NULL, 'both');

-- Insert sample reviews
INSERT INTO `reviews` (`user_id`, `book_id`, `rating`, `comment`) VALUES
(3, 1, 5, 'Absolutely magical! One of the best books I\'ve ever read. The world-building is incredible and the characters are so memorable.'),
(3, 2, 5, 'A chilling masterpiece that feels more relevant than ever. Orwell\'s vision is both terrifying and thought-provoking.'),
(3, 3, 4, 'Stephen King at his finest. The psychological horror is intense and the atmosphere is perfectly crafted. A bit slow in the middle but worth it.'),
(3, 6, 5, 'Mind-blowing insights into success! This book completely changed how I think about achievement and talent. Highly recommended!'),
(3, 8, 5, 'Timeless wisdom that still applies today. The principles in this book have helped me in both my personal and professional life.');

-- Update AUTO_INCREMENT for categories and authors
ALTER TABLE `categories` AUTO_INCREMENT = 11;
ALTER TABLE `authors` AUTO_INCREMENT = 11;

