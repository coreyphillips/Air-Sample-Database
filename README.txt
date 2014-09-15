Folder- "Classes"
This folder currently houses all PHPExcel classes.

Folder- "files"
The four files in the folder "files" (One.xls, Two.xls, Three.xls and Four.xls) are the four types of files the Louisiana Bucket Brigade receives their air sample results in.

Folder- "uploads"
This folder stores all air sample files after upload and insertion into the database.

File- "airsamples.sql"
Airsample database.

File- "connection.php"
Database connection script.

File- "fileupload.php"
Bare bones HTML upload form.

File- "functions.php"
The two functions contained in the "functions.php" script (Functions "one" & "two") are able to detect, read and insert the two air sample result files received by the Louisiana Bucket Brigade into the "airsamples.sql" database.

File- "truncatetables.php"
This file truncates the "airsamples.sql" database for easier testing.


Get Started:

1. Setup the Airsample Database by using the "airsample.sql" file

2. Navigate to "localhost/fileupload.php"

3. Click browse and navigate to and use the example files in the "files" folder.

4. Click "Submit" and observe the "airsample.sql" database tables for population.



