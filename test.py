# Example Python script to create a file
file_path = '/Applications/XAMPP/xamppfiles/htdocs/Health_care_bot/data_dashboard.php'

try:
    with open(file_path, 'w') as file:
        file.write('<?php\n')
        file.write('echo "Hello, World!";\n')
        file.write('?>\n')
    print(f'File {file_path} created successfully.')
except IOError as e:
    print(f'Error creating file: {e}')
