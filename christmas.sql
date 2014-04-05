DROP DATABASE IF EXISTS Christmas;
CREATE DATABASE Christmas;
USE Christmas;

#Stores all phone types, as well as allowing to add "other" reason
CREATE TABLE PhoneType
(
    id INT NOT NULL AUTO_INCREMENT,
    description VARCHAR(20),
    PRIMARY KEY(id)
);

INSERT INTO PhoneType (description) VALUES("Home");
INSERT INTO PhoneType (description) VALUES("Cell");
INSERT INTO PhoneType (description) VALUES("Work");
INSERT INTO PhoneType (description) VALUES("Other");

CREATE TABLE Language
(
    id INT NOT NULL AUTO_INCREMENT,
    languageName VARCHAR(20),
    PRIMARY KEY(id)
);

INSERT INTO Language (languageName) VALUES ("English");
INSERT INTO Language (languageName) VALUES ("Spanish");

#Represents a person filling out a form
CREATE TABLE PersonOrdering
(
    id INT NOT NULL AUTO_INCREMENT,
    firstName VARCHAR(20),
    lastName VARCHAR(20),
    email VARCHAR(40),
    
    primaryPhoneId INT NOT NULL DEFAULT 1,
    primaryPhoneNum VARCHAR(20) NOT NULL DEFAULT "",
    secondaryPhoneId INT NOT NULL DEFAULT 1,
    secondaryPhoneNum VARCHAR(20) NOT NULL DEFAULT "",
    
    languageId INT NOT NULL DEFAULT 1,
    
    notes VARCHAR(30) NOT NULL DEFAULT "",
    
    #add if we want a food or clothing order
    
    PRIMARY KEY(id),
    FOREIGN KEY(primaryPhoneId) REFERENCES PhoneType(id),
    FOREIGN KEY(secondaryPhoneId) REFERENCES PhoneType(id),
    FOREIGN KEY(languageId) REFERENCES Language(id)
);

CREATE INDEX part_of_name ON PersonOrdering (lastName(20));

#Will insert a default person to just add addresses without having a  head of household
INSERT INTO PersonOrdering (firstName, lastName, email) VALUES ("No", "Name", "ddd");
INSERT INTO PersonOrdering (firstName, lastName, email) VALUES ("Other", "Name", "ff");

#Not sure if primaryGaurdianId should not be added for flexibility by admins, will have today as birthday by default
CREATE TABLE Children
(
    cid INT NOT NULL AUTO_INCREMENT,
    firstName VARCHAR(20), 
    lastName VARCHAR(20),
    age INT NOT NULL,
    childID VARCHAR(3),
    childIDNo VARCHAR(30),
    primaryGaurdianId INT NOT NULL DEFAULT 1,
    PRIMARY KEY(cid),
    FOREIGN KEY(primaryGaurdianId) REFERENCES PersonOrdering(id) 
);

#Default child name
INSERT INTO Children (firstName, lastName, age) VALUES ("No", "Name", 0);

#Table to hold users (volunteers and admins) of the application
CREATE TABLE Members
(
	id INT NOT NULL AUTO_INCREMENT,
	fname VARCHAR(30) NOT NULL,
	lname VARCHAR(30) NOT NULL,
	initials VARCHAR(5),
	email VARCHAR(40) NOT NULL,
	username VARCHAR(40) NOT NULL,
	password VARCHAR(60) NOT NULL,
	role VARCHAR(10) NOT NULL,
	PRIMARY KEY(id)
);

#Need to see if can combine ordered by and ordered for ino primary key
#Need to add structure to add info
#may be better if put ordered by id in seperate table
CREATE TABLE ClothingOrders
(
    coid INT NOT NULL AUTO_INCREMENT,
    orderedById INT,
    orderedForId INT,
    gender VARCHAR(10),
    infantOutfitSize VARCHAR(35),
    infantOutfitSpecial VARCHAR(35),
    jeansSize VARCHAR(35),
    jeansSpecial VARCHAR(35),
    shirtSize VARCHAR(35),
    shirtSpecial VARCHAR(35),
    socksSize VARCHAR(35),
    socksSpecial VARCHAR(35),
    underwearSize VARCHAR(35),
    diaperSize VARCHAR(35),
    uodSpecial VARCHAR(35),
    uniIO VARCHAR(35),
    uniSocks VARCHAR(35),
    uniDiapers VARCHAR(35),
    notes VARCHAR(50),
    checklist VARCHAR(50),
    completedBy VARCHAR(3),
    PRIMARY KEY(coid),
    FOREIGN KEY(orderedById) REFERENCES PersonOrdering(id),
    FOREIGN KEY(orderedForId) REFERENCES Children(cid)
);

#for quick lookup when querying if there are any people in a house ordering clothes
CREATE INDEX peopleOrderingClothes ON ClothingOrders (orderedById, coid);

#this stores unique addresses with int primary keys for quick linking
CREATE TABLE Addresses
(
    aid INT NOT NULL AUTO_INCREMENT,
    houseNumber VARCHAR(30) NOT NULL DEFAULT "",
    streetName  VARCHAR(30) NOT NULL DEFAULT "",
    city        VARCHAR(20) NOT NULL DEFAULT "",
    zipCode     VARCHAR(12) NOT NULL DEFAULT "",
    PRIMARY KEY (aid), 
    CONSTRAINT validAddress UNIQUE (houseNumber, streetName, city, zipCode)
);

#default address
INSERT IGNORE INTO Addresses (houseNumber, streetName, city, zipCode)
VALUES ("No", "Address", "Assigned", "here");

#this represents people in houses (it can be one person to a house if there is a performance issue)
CREATE TABLE peopleInHouse 
(
    pid INT,
    aid INT,
    PRIMARY KEY(aid,pid),
    FOREIGN KEY (pid) REFERENCES PersonOrdering(id),
    FOREIGN KEY (aid) REFERENCES Addresses(aid)
);

#Can be primary key if performance is an issue
CREATE TABLE HeadOfHousehold
(
    hid INT,
    pid INT,
    #done to allow to keep multiple head of household
    PRIMARY KEY (hid),
    FOREIGN KEY (hid) REFERENCES Addresses(aid),
    FOREIGN KEY (pid) REFERENCES PersonOrdering(id)
);

#Represents a simple food order
CREATE TABLE FoodOrder
(
    aid INT,
    numPeople INT,
    needDelievery BOOL NOT NULL DEFAULT 0,
    PRIMARY KEY (aid),
    FOREIGN KEY (aid) REFERENCES Addresses(aid)
);