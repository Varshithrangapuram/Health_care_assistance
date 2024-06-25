import sys
import warnings
import pandas as pd
import joblib

# Ignore all warnings
warnings.filterwarnings("ignore")

# Load the trained model
try:
    model = joblib.load('/Applications/XAMPP/xamppfiles/htdocs/Health_care_bot/tfidf_svm_symptom_model.pkl')
except Exception as e:
    print(f"Error loading model: {e}")
    sys.exit(1)

# Function to predict disease
def predict_disease(symptoms):
    new_text = [symptoms]
    try:
        predicted_disease = model.predict(new_text)
        return predicted_disease[0]
    except Exception as e:
        return f"Error predicting disease: {e}"

# Get input from command line arguments (PHP will pass input as argument)
if __name__ == "__main__":
    if len(sys.argv) > 1:
        symptoms = sys.argv[1]
        predicted_disease = predict_disease(symptoms)
        print(predicted_disease)
    else:
        print("No symptoms provided")
