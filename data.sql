CREATE TABLE user (
    UserID INT NOT NULL AUTO_INCREMENT,
    Email VARCHAR(100) NOT NULL,
    Username VARCHAR(30) NOT NULL,
    Password VARCHAR(30) NOT NULL,
    PRIMARY KEY (UserID),
    UNIQUE (Email),
    UNIQUE (Username)
);
CREATE TABLE admin (
    AdminID INT NOT NULL AUTO_INCREMENT,
    FirstName VARCHAR(30) NOT NULL,
    Surname VARCHAR(30) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    Username VARCHAR(30) NOT NULL,
    Password VARCHAR(30) NOT NULL,
    PRIMARY KEY (AdminID),
    UNIQUE (Email),
    UNIQUE (Username)
);
CREATE TABLE product (
    ProductNo INT NOT NULL AUTO_INCREMENT,
    Barcode VARCHAR(100) NOT NULL,
    Name VARCHAR(100) NOT NULL,
    PRIMARY KEY (ProductNo),
    UNIQUE (Barcode)
);
CREATE TABLE grocery (
    GroceryNo INT NOT NULL AUTO_INCREMENT,
    Barcode VARCHAR(100),
    Name VARCHAR(100) NOT NULL,
    ExpiryDate DATE NOT NULL,
    UserID INT NOT NULL,
    PRIMARY KEY (GroceryNo)
);
CREATE TABLE alert (
    AlertNo INT NOT NULL AUTO_INCREMENT,
    UserID INT NOT NULL,
    GroceryNo INT NOT NULL,
    PRIMARY KEY (AlertNo)
);
CREATE TABLE request (
    RequestNo INT NOT NULL AUTO_INCREMENT,
    ProductName VARCHAR(100) NOT NULL,
    ProductBarcode VARCHAR(100) NOT NULL,
    UserID INT NOT NULL,
    PRIMARY KEY (RequestNo)
);