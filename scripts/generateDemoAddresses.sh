echo "##################################################"
echo
echo This script generate a test address content
echo "in src/csv/addresses/import/en_GB.csv"
echo 
echo After the generation run python3 build.py
echo 
echo "##################################################"
read -p "Press enter to continue"
php scripts/php/listGenerator.php >src/csv/addresses/import/en_GB.csv
echo Generated
echo