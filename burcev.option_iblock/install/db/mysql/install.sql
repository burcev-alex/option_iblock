create table if not exists b_burcev_option_iblock
(
	ID VARCHAR(50) not null,
	USER_ID int null,
	USER_FILE_ID int null,
	NAME varchar(255),
	primary key (ID)
);

ALTER TABLE `b_burcev_option_iblock` ADD UNIQUE (
`ID`
);

ALTER TABLE `b_burcev_option_iblock` CHANGE `ID` `ID` INT( 50 ) NOT NULL AUTO_INCREMENT ;