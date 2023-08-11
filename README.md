# Calendar-Maker

Newtown High School Calendar Maker

Spreadsheet Setup:
--------

All special types of days, have special codes. When building the year calendar use the following guide:

Day | Day Code | Type
----|------|-------|
Early Dismissal |`A`, `B`, `C`, `D`, `E`, `F`, `G`, `H`| 1 |
PLC Days |`A`, `B`, `C`, `D`, `E`, `F`, `G`, `H`| 2 |
Midterms and Finals |`M1`, `M2`, `M3`, `M4` or `Y1`, `Y2`, `Y3`, `Y4`| 3 |
Extended Advisory |`A`, `B`, `C`, `D`, `E`, `F`, `G`, `H`| 4 |
Two Hour Delay |`A`, `B`, `C`, `D`, `E`, `F`, `G`, `H`| 5 |
????? |`A`, `B`, `C`, `D`, `E`, `F`, `G`, `H`| 6 |
First Day of School |`S`| 7 | 
Second Day of School |`SS`| 8 | 
Third Day of School |`SSS`| 9 | 

### Formatting Info

All dates must be in `YYYY-MM-DD` format. Must be `-` and not `/`


Update for New Year
-------------------

Updating the app for a new year is quite simple. It involves only a few steps.

1. Collect new year CSV files
    - These files are usually provided by the Executive Administrative Assistant
2. Update dates inside app code and add CSVs to repository
    - Search for last year (ex `2022`) in the code and update all references to current school year
    - An example commit from 2022 can be found [here](https://github.com/devinmatte/NHS-Calendar-Maker/commit/c2cc51ee7589cb245ac98e4a4e070af3a123282c)
3. Upload new CSVs to mysql (reach out to @devinmatte for access)
    - a. Find the DAYS and BELLS tables
    - b. Import new DAYS file to the DAYS table
    - c. Truncate the BELLS table, and import new BELLS CSV to BELLS table
    - d. If there are errors, check the formatting guide above and match the new files to it. Correct errors as needed, and repeat steps b-c
