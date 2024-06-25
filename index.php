<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Healthcare Bot</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 60%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        input[type="text"] {
            width: calc(100% - 22px);
            padding: 10px;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            padding: 12px 20px;
            font-size: 18px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        .response {
            margin-top: 20px;
            background-color: #e9f7ef;
            border: 1px solid #d4efdf;
            padding: 20px;
            border-radius: 4px;
        }
        .history {
            text-align: left;
            margin-top: 20px;
            background-color: #fdfdfd;
            border: 1px solid #eee;
            padding: 20px;
            border-radius: 4px;
        }
        .history h3 {
            margin-top: 0;
            color: #555;
        }
        .history-item {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Healthcare Bot</h2>
        <div class="history">
            <h3>History:</h3>
            <div id="history-list">
                <!-- History items will be dynamically added here -->
            </div>
        </div>

        <div id="responseContainer" class="response">
            <!-- Response will be dynamically inserted here -->
        </div>

        <form id="symptomsForm" action="process_symptoms.php" method="post">
            <input type="text" id="symptoms" name="symptoms" placeholder="Enter your symptoms..." required>
            <br>
            <input type="submit" value="Submit">
        </form>
    </div>

    <script>
        // Handle form submission using AJAX
        document.getElementById('symptomsForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            var form = this;
            var formData = new FormData(form);

            // Send AJAX request
            var xhr = new XMLHttpRequest();
            xhr.open(form.method, form.action, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    // Update response container with the received data
                    document.getElementById('responseContainer').innerHTML = xhr.responseText;
                    // Clear form after successful submission (optional)
                    form.reset();
                    // Refresh history after submission (optional)
                    fetchHistory();
                }
            };
            xhr.send(formData);
        });

        // Fetch and display history dynamically
        function fetchHistory() {
            fetch('fetch_history.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('history-list').innerHTML = data;
            });
        }

        // Fetch history initially when page loads
        fetchHistory();

        // Set interval to refresh history every 10 seconds (adjust as needed)
        setInterval(fetchHistory, 10000); // Every 10 seconds
    </script>
</body>
</html>
