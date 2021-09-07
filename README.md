## STEP1 
Insert teams into the DB after creating tables with php artisan:migrate
INSERT INTO teams (name, flag, is_idle, is_active) VALUES ('England', 'gb-eng', 0, 1);
INSERT INTO teams (name, flag, is_idle, is_active) VALUES ('Czech Republic', 'cz', 0, 1);
INSERT INTO teams (name, flag, is_idle, is_active) VALUES ('Ukraine', 'uk', 0, 1);
INSERT INTO teams (name, flag, is_idle, is_active) VALUES ('Portugal', 'pt', 0, 1);
INSERT INTO teams (name, flag, is_idle, is_active) VALUES ('Germany', 'de', 0, 1);
INSERT INTO teams (name, flag, is_idle, is_active) VALUES ('Netherlands', 'nl', 0, 1);
INSERT INTO teams (name, flag, is_idle, is_active) VALUES ('Switzerland', 'ch', 0, 1);
INSERT INTO teams (name, flag, is_idle, is_active) VALUES ('Denmark', 'dk', 0, 1);
INSERT INTO teams (name, flag, is_idle, is_active) VALUES ('Croatia', 'hr', 0, 1);
INSERT INTO teams (name, flag, is_idle, is_active) VALUES ('Wales', 'gb-wls', 0, 1);
INSERT INTO teams (name, flag, is_idle, is_active) VALUES ('Spain', 'es', 0, 1);
INSERT INTO teams (name, flag, is_idle, is_active) VALUES ('Sweden', 'se', 0, 1);
INSERT INTO teams (name, flag, is_idle, is_active) VALUES ('Poland', 'pl', 0, 1);
INSERT INTO teams (name, flag, is_idle, is_active) VALUES ('Austria', 'at', 0, 1);
INSERT INTO teams (name, flag, is_idle, is_active) VALUES ('France', 'fr', 0, 1);
INSERT INTO teams (name, flag, is_idle, is_active) VALUES ('Turkey', 'tr', 0, 1);
INSERT INTO teams (name, flag, is_idle, is_active) VALUES ('Belgium', 'be', 0, 1);
INSERT INTO teams (name, flag, is_idle, is_active) VALUES ('Russia', 'ru', 0, 1);
INSERT INTO teams (name, flag, is_idle, is_active) VALUES ('Italy', 'it', 0, 1);
INSERT INTO teams (name, flag, is_idle, is_active) VALUES ('Finland', 'fi', 0, 1);

## STEP2
Register a new account. 
By default it will be a normal user, if u want to make it an Admin user, update is_admin value to 1 in Users table.

## STEP3
As an Admin Go to NEW MATCH tab and create a new match after selecting 2 teams and start date for the match

## STEP4 
As a normal user go to NEW BETS tab and place a bet on a team. The bets will be viewable until 30 minutes before the match starts. After that the match will dissapear from the tab.

## STEP5 
As an Admin, you can close the match in the UPDATE MATCH RESULTS tab. You can only see matches that are beyond the end_date (by default start_date +2h).

## How it works
- As a normal user you can only see the Dashboard.
- As an admin you can only see the Backoffice section.
- You can check all bets made by players in the ALL BETS tab. The bets are viewable only if the match is not bettable any more.
- In the USER RANKING tab you can see the rankings.
- By closing the match, the users that placed their bet on the right team get 1 point, otherwise 0.

