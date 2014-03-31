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
    
    PRIMARY KEY(id),
    FOREIGN KEY(primaryPhoneId) REFERENCES PhoneType(id),
    FOREIGN KEY(secondaryPhoneId) REFERENCES PhoneType(id),
    FOREIGN KEY(languageId) REFERENCES Language(id)
);

#Will insert a default person to just add addresses without having a  head of household
INSERT INTO PersonOrdering (firstName, lastName, email) VALUES ("No", "Name", "ddd");
INSERT INTO PersonOrdering (firstName, lastName, email) VALUES ("Other", "Name", "ff");

#Not sure if primaryGaurdianId should not be added for flexibility by admins, will have today as birthday by default
CREATE TABLE Childeren
(
    cid INT NOT NULL AUTO_INCREMENT,
    firstName VARCHAR(20),
    lastName VARCHAR(20),
    dateOfBirth DATE NOT NULL, not sure if needed
    primaryGaurdianId INT NOT NULL DEFAULT 1,
    PRIMARY KEY(cid),
    FOREIGN KEY(primaryGaurdianId) REFERENCES PersonOrdering(id) 
);

#Default child name
INSERT INTO Childeren (firstName, lastName, dateOfBirth) VALUES ("No", "Name", CURDATE());

#Need to see if can combine ordered by and ordered for ino primary key
#Need to add structure to add info
CREATE TABLE ClothingOrders
(
    coid INT NOT NULL AUTO_INCREMENT,
    orderedById INT,
    orderedForId INT,
    gender VARCHAR(10);
    infantOutfitSize VARCHAR(30);
    infantOutfitSpecial VARCHAR(30);
    jeansSize VARCHAR(30);
    jeansSpecial VARCHAR(30);
    shirtSize VARCHAR(30);
    shirtSpecial VARCHAR(30);
    socksSize VARCHAR(30);
    socksSpecial VARCHAR(30);
    underwearSize VARCHAR(30);
    diaperSize VARCHAR(30);
    uodSpecial VARCHAR(30);
    PRIMARY KEY(coid),
    FOREIGN KEY(orderedById) REFERENCES PersonOrdering(id),
    FOREIGN KEY(orderedForId) REFERENCES Childeren(cid)
);

#this stores unique addresses with int primary keys for quick linking
CREATE TABLE Addresses
(
    aid INT NOT NULL AUTO_INCREMENT,
    houseNumber VARCHAR(10) NOT NULL,
    streetName  VARCHAR(30) NOT NULL,
    city        VARCHAR(20) NOT NULL,
    zipCode     VARCHAR(12) NOT NULL,
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
    #PRIMARY KEY(pid)
    FOREIGN KEY (pid) REFERENCES PersonOrdering(id),
    FOREIGN KEY (aid) REFERENCES Addresses(aid)
);

#Can be primary key if performance is an issue
CREATE TABLE HeadOfHousehold
(
    hid INT,
    pid INT,
    FOREIGN KEY (hid) REFERENCES Addresses(aid),
    FOREIGN KEY (pid) REFERENCES PersonOrdering(id)
);