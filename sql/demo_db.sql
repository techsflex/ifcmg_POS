INSERT INTO `company` (`companyID`, `companyname`, `address1`,`address2`,`city`,`phone`) VALUES
(1, 'Demo Company', 'XXX XXXX TOWERS SHAHRA-E-FAISAL', 'P.E.C.H.S', 'Karachi', '+92-21-39xx xxxx'),
(2, 'Fake Company', 'XXX XXXX TOWERS DHA PHASE-II', 'P.E.C.H.S', 'Karachi', '+92-21-35xx xxxx');

INSERT INTO `cat` (`catID`, `catname`, `company_companyID`) VALUES
(1, 'New', 1),
(2, 'Appetizers', 1),
(3, 'Main Course', 1),
(4, 'Dessert', 1),
(5, 'Sandwich', 2),
(6, 'Burger', 2),
(7, 'Noodles', 2);

/*INSERT INTO `held` (`heldID`, `ordertype`, `datetime`, `subtotal`, `taxpaid`, `discount`, `grandtotal`, `breakdown`, `kitchenstatus`, `company_companyID`) VALUES
(1, 'Dine-In', '2018-05-24 01:39:54', 685, 787.75, 0, 788, '[{\"productCode\":\"MCL005\",\"productName\":\"Thai Cashew Nut Chicken\",\"productPrice\":\"685.00\",\"productQuantity\":\"1\",\"productTotal\":\"685.00\"}]', 0, 1),
(2, 'Dine-In', '2018-07-01 05:40:16', 915, 1052.25, 0, 1052, '[{\"productCode\":\"DES001\",\"productName\":\"Italian Bread &amp; Butter Pudding\",\"productPrice\":\"490.00\",\"productQuantity\":\"1\",\"productTotal\":\"490.00\"},{\"productCode\":\"SKU007\",\"productName\":\"Chicken Cigars\",\"productPrice\":\"425.00\",\"productQuantity\":\"1\",\"productTotal\":\"425.00\"}]', 0, 1),
(3, 'Take-Away', '2018-09-15 17:13:30', 875, 1006.25, 0, 1006, '[{\"productCode\":\"MBL001\",\"productName\":\"Beef Steak in Mushroom Sauce\",\"productPrice\":\"875.00\",\"productQuantity\":\"1\",\"productTotal\":\"875.00\"}]', 1, 1),
(4, 'Take-Away', '2018-11-11 21:22:53', 2430, 2794.5, 0, 2795, '[{\"productCode\":\"MBL002\",\"productName\":\"Beef Steak with Pepper Sauce\",\"productPrice\":\"825.00\",\"productQuantity\":\"1\",\"productTotal\":\"825.00\"},{\"productCode\":\"MCL002\",\"productName\":\"Stuffed Chicken\",\"productPrice\":\"645.00\",\"productQuantity\":\"1\",\"productTotal\":\"645.00\"},{\"productCode\":\"DES002\",\"productName\":\"Molten Lava Cake\",\"productPrice\":\"475.00\",\"productQuantity\":\"1\",\"productTotal\":\"475.00\"},{\"productCode\":\"DES004\",\"productName\":\"Tiramisu\",\"productPrice\":\"485.00\",\"productQuantity\":\"1\",\"productTotal\":\"485.00\"}]', 0, 1);


INSERT INTO `orders` (`orderID`, `paymentID`, `ordertype`, `datetime`, `subtotal`, `taxpaid`, `discount`, `grandtotal`, `breakdown`, `kitchenstatus`, `company_companyID`) VALUES
(1, 'Cash', 'Dine-In', '2018-05-24 01:37:46', 1945, 2236.75, 0, 2237, '[{\"productCode\":\"SKU001\",\"productName\":\"Grilled Calamari\",\"productPrice\":\"395.00\",\"productQuantity\":\"1\",\"productTotal\":\"395.00\"},{\"productCode\":\"MBL003\",\"productName\":\"Beef Steak in Bearnaise Sauce\",\"productPrice\":\"925.00\",\"productQuantity\":\"1\",\"productTotal\":\"925.00\"},{\"productCode\":\"MCL001\",\"productName\":\"Italian Parmesan Crusted Chicken\",\"productPrice\":\"625.00\",\"productQuantity\":\"1\",\"productTotal\":\"625.00\"}]', 1, 1),
(2, 'Cash', 'Take-Away', '2018-05-27 01:38:11', 1195, 1374.25, 0, 1374, '[{\"productCode\":\"SKU004\",\"productName\":\"Artichoke and Spinach Dip\",\"productPrice\":\"510.00\",\"productQuantity\":\"1\",\"productTotal\":\"510.00\"},{\"productCode\":\"MCL005\",\"productName\":\"Thai Cashew Nut Chicken\",\"productPrice\":\"685.00\",\"productQuantity\":\"1\",\"productTotal\":\"685.00\"}]', 0, 1),
(3, 'Cash', 'Dine-In', '2018-05-29 01:39:09', 2640, 3036, 0, 3036, '[{\"productCode\":\"SKU005\",\"productName\":\"Thai Prawn Cake\",\"productPrice\":\"625.00\",\"productQuantity\":\"1\",\"productTotal\":\"625.00\"},{\"productCode\":\"MBL004\",\"productName\":\"Beef Steak in Mustard Sauce\",\"productPrice\":\"855.00\",\"productQuantity\":\"1\",\"productTotal\":\"855.00\"},{\"productCode\":\"DES005\",\"productName\":\"Peanut Butter &amp; Chocolate Mousse\",\"productPrice\":\"495.00\",\"productQuantity\":\"1\",\"productTotal\":\"495.00\"},{\"productCode\":\"MCL004\",\"productName\":\"Morrocan Chicken\",\"productPrice\":\"665.00\",\"productQuantity\":\"1\",\"productTotal\":\"665.00\"}]', 1, 1),
(4, 'Card', 'Take-Away', '2018-05-29 01:39:36', 9135, 10505.2, 0, 10505, '[{\"productCode\":\"MCL001\",\"productName\":\"Italian Parmesan Crusted Chicken\",\"productPrice\":\"625.00\",\"productQuantity\":\"1\",\"productTotal\":\"625.00\"},{\"productCode\":\"MCL002\",\"productName\":\"Stuffed Chicken\",\"productPrice\":\"645.00\",\"productQuantity\":\"1\",\"productTotal\":\"645.00\"},{\"productCode\":\"MCL003\",\"productName\":\"Tarragon Chicken\",\"productPrice\":\"625.00\",\"productQuantity\":\"1\",\"productTotal\":\"625.00\"},{\"productCode\":\"MCL004\",\"productName\":\"Morrocan Chicken\",\"productPrice\":\"665.00\",\"productQuantity\":\"1\",\"productTotal\":\"665.00\"},{\"productCode\":\"MCL005\",\"productName\":\"Thai Cashew Nut Chicken\",\"productPrice\":\"685.00\",\"productQuantity\":\"1\",\"productTotal\":\"685.00\"},{\"productCode\":\"MBL001\",\"productName\":\"Beef Steak in Mushroom Sauce\",\"productPrice\":\"875.00\",\"productQuantity\":\"1\",\"productTotal\":\"875.00\"},{\"productCode\":\"MBL004\",\"productName\":\"Beef Steak in Mustard Sauce\",\"productPrice\":\"855.00\",\"productQuantity\":\"1\",\"productTotal\":\"855.00\"},{\"productCode\":\"MBL003\",\"productName\":\"Beef Steak in Bearnaise Sauce\",\"productPrice\":\"925.00\",\"productQuantity\":\"1\",\"productTotal\":\"925.00\"},{\"productCode\":\"MBL002\",\"productName\":\"Beef Steak with Pepper Sauce\",\"productPrice\":\"825.00\",\"productQuantity\":\"1\",\"productTotal\":\"825.00\"},{\"productCode\":\"DES001\",\"productName\":\"Italian Bread &amp; Butter Pudding\",\"productPrice\":\"490.00\",\"productQuantity\":\"1\",\"productTotal\":\"490.00\"},{\"productCode\":\"DES002\",\"productName\":\"Molten Lava Cake\",\"productPrice\":\"475.00\",\"productQuantity\":\"1\",\"productTotal\":\"475.00\"},{\"productCode\":\"DES003\",\"productName\":\"Creme Brulee\",\"productPrice\":\"465.00\",\"productQuantity\":\"1\",\"productTotal\":\"465.00\"},{\"productCode\":\"DES005\",\"productName\":\"Peanut Butter &amp; Chocolate Mousse\",\"productPrice\":\"495.00\",\"productQuantity\":\"1\",\"productTotal\":\"495.00\"},{\"productCode\":\"DES004\",\"productName\":\"Tiramisu\",\"productPrice\":\"485.00\",\"productQuantity\":\"1\",\"productTotal\":\"485.00\"}]', 1, 1),
(5, 'Card', 'Dine-In', '2018-07-15 01:41:52', 5245, 6031.75, 0, 6032, '[{\"productCode\":\"SKU001\",\"productName\":\"Grilled Calamari\",\"productPrice\":\"395.00\",\"productQuantity\":\"1\",\"productTotal\":\"395.00\"},{\"productCode\":\"SKU003\",\"productName\":\"Crispy Chicken Wings\",\"productPrice\":\"410.00\",\"productQuantity\":\"1\",\"productTotal\":\"410.00\"},{\"productCode\":\"SKU004\",\"productName\":\"Artichoke and Spinach Dip\",\"productPrice\":\"510.00\",\"productQuantity\":\"1\",\"productTotal\":\"510.00\"},{\"productCode\":\"MCL003\",\"productName\":\"Tarragon Chicken\",\"productPrice\":\"625.00\",\"productQuantity\":\"1\",\"productTotal\":\"625.00\"},{\"productCode\":\"MBL002\",\"productName\":\"Beef Steak with Pepper Sauce\",\"productPrice\":\"825.00\",\"productQuantity\":\"1\",\"productTotal\":\"825.00\"},{\"productCode\":\"MBL001\",\"productName\":\"Beef Steak in Mushroom Sauce\",\"productPrice\":\"875.00\",\"productQuantity\":\"1\",\"productTotal\":\"875.00\"},{\"productCode\":\"MCL004\",\"productName\":\"Morrocan Chicken\",\"productPrice\":\"665.00\",\"productQuantity\":\"1\",\"productTotal\":\"665.00\"},{\"productCode\":\"DES003\",\"productName\":\"Creme Brulee\",\"productPrice\":\"465.00\",\"productQuantity\":\"1\",\"productTotal\":\"465.00\"},{\"productCode\":\"DES002\",\"productName\":\"Molten Lava Cake\",\"productPrice\":\"475.00\",\"productQuantity\":\"1\",\"productTotal\":\"475.00\"}]', 1, 1),
(6, 'Cash', 'Take-Away', '2018-07-15 01:42:08', 425, 488.75, 0, 489, '[{\"productCode\":\"SKU007\",\"productName\":\"Chicken Cigars\",\"productPrice\":\"425.00\",\"productQuantity\":\"1\",\"productTotal\":\"425.00\"}]', 1, 1),
(7, 'Cash', 'Dine-In', '2018-05-15 01:42:47', 3730, 4289.5, 0, 4290, '[{\"productCode\":\"SKU001\",\"productName\":\"Grilled Calamari\",\"productPrice\":\"395.00\",\"productQuantity\":\"1\",\"productTotal\":\"395.00\"},{\"productCode\":\"SKU005\",\"productName\":\"Thai Prawn Cake\",\"productPrice\":\"625.00\",\"productQuantity\":\"1\",\"productTotal\":\"625.00\"},{\"productCode\":\"MCL003\",\"productName\":\"Tarragon Chicken\",\"productPrice\":\"625.00\",\"productQuantity\":\"1\",\"productTotal\":\"625.00\"},{\"productCode\":\"MBL003\",\"productName\":\"Beef Steak in Bearnaise Sauce\",\"productPrice\":\"925.00\",\"productQuantity\":\"1\",\"productTotal\":\"925.00\"},{\"productCode\":\"MCL004\",\"productName\":\"Morrocan Chicken\",\"productPrice\":\"665.00\",\"productQuantity\":\"1\",\"productTotal\":\"665.00\"},{\"productCode\":\"DES005\",\"productName\":\"Peanut Butter &amp; Chocolate Mousse\",\"productPrice\":\"495.00\",\"productQuantity\":\"1\",\"productTotal\":\"495.00\"}]', 0, 1);
*/
INSERT INTO `products` (`skuID`, `productID`, `productname`, `productprice`, `cat_catID`, `description`) VALUES
(1, 'APT001', 'Grilled Calamari', '395', 2, ''),
(2, 'APT002', 'Chilli Garlic Prawns', '625', 2, ''),
(3, 'APT003', 'Crispy Chicken Wings', '410', 2, ''),
(4, 'APT004', 'Artichoke and Spinach Dip', '510', 2, ''),
(5, 'APT005', 'Thai Prawn Cake', '625', 2, ''),
(6, 'APT006', 'Fried Calamari', '395', 2, ''),
(7, 'DES001', 'Italian Bread and Butter Pudding', '490', 4, ''),
(8, 'DES002', 'Molten Lava Cake', '475', 4, ''),
(9, 'DES003', 'Creme Brulee', '465', 4, ''),
(10, 'DES004', 'Tiramisu', '485', 4, ''),
(11, 'DES005', 'Peanut Butter and Chocolate Mousse', '495', 4, ''),
(12, 'MCL001', 'Italian Parmesan Crusted Chicken', '625', 3, ''),
(13, 'MCL002', 'Stuffed Chicken', '645', 3, ''),
(14, 'MCL003', 'Tarragon Chicken', '625', 3, ''),
(15, 'MCL004', 'Morrocan Chicken', '665', 3, ''),
(16, 'MCL005', 'Thai Cashew Nut Chicken', '685', 3, ''),
(17, 'MBL001', 'Beef Steak in Mushroom Sauce', '875', 3, ''),
(18, 'MBL002', 'Beef Steak with Pepper Sauce', '825', 3, ''),
(19, 'MBL003', 'Beef Steak in Bearnaise Sauce', '925', 3, ''),
(20, 'MBL004', 'Beef Steak in Mustard Sauce', '855', 3, ''),
(21, 'DES006', 'Ras Malai', '250', 4, ''),
(22, 'MCL006', 'Chicken Manchurian', '525', 3, ''),
(23, 'MCL007', 'Chicken Fajita', '412', 3, ''),

(24, 'SAN001', 'Grilled Cheese Sandwich', '401', 5, ''),
(25, 'SAN002', 'Club Sandwich', '519', 5, ''),
(26, 'SAN003', 'Chicken Cheese Sandwich', '479', 5, ''),
(27, 'BUR001', 'Zinger Burger', '555', 6, ''),
(28, 'BUR002', 'Cheese Burger', '495', 6, ''),
(29, 'BUR003', 'Quarter Pounder', '515', 6, ''),
(30, 'NOD001', 'Chicken Alfredo Noodle', '325', 7, ''),
(31, 'NOD002', 'Beef Bolognese Noodle', '365', 7, ''),
(32, 'NOD003', 'Pad Thai Noodle', '435', 7, '');