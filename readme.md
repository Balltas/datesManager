Practical Test

You should create a small utility to help a company determine the dates on which they need to pay
salaries and bonuses to their Sales Department.
This should be completed with the intention to be run with PHP5 or above.
The script should generate a CSV format file with the filename format of dates_yyyymmdd.csv
The file must contain the following fields: Month Name, Salary Date, Bonus Date
The script should generate the next 12 months worth of dates.
The company has provided the following information for the payment dates.

1. Staff get a regular fixed base monthly salary, plus a monthly bonus
2. The base salaries are paid on the last day of the month, unless that day lands on a weekend. In
that case, salaries are paid before the weekend.
3. Bonuses are paid on the 1st Wednesday after the 10th of every month.
4. Bonus and Salary dates cannot occur in the same week.

Code should be provided in a zipped file with the format of forename_surname_yyyymmdd.zip

---
- If the base salary is paid on the last day of the months or 2 days before AND bonuses are payed
first wednesday after 10th day, then it is impossible that bonus and salary dates could occur on the same week
so the 4th step is irrelevant.
- As you can see all the dates are solved simply by math equations so no cycles needed that prolongs execution time.
It is the simple and yet most effective way to  solve this.
- Just run index.php and it should generate CSV file on the same dir.
- I was using PHP 7.1.9