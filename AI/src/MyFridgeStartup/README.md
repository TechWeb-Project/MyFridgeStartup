# MyFridgeStartup

MyFridgeStartup is a project that provides a recipe generation service based on available ingredients and time constraints. The project includes two implementations of the service using different web frameworks.

## Project Structure

```
MyFridgeStartup
├── AI
│   └── src
│       ├── app.py       # Original implementation using Flask
│       ├── app2.py      # New implementation using FastAPI
└── README.md            # Project documentation
```

## Installation

To set up the project, you need to have Python installed on your machine. You can create a virtual environment and install the required dependencies.

1. Clone the repository:
   ```
   git clone <repository-url>
   cd MyFridgeStartup
   ```

2. Create a virtual environment:
   ```
   python -m venv venv
   source venv/bin/activate  # On Windows use `venv\Scripts\activate`
   ```

3. Install the required packages:
   For Flask implementation:
   ```
   pip install Flask flask-cors
   ```

   For FastAPI implementation:
   ```
   pip install fastapi uvicorn ollama
   ```

## Usage

### Running the Flask Application

To run the Flask application, execute the following command:
```
python AI/src/app.py
```
The application will start on `http://127.0.0.1:5000`, and you can send POST requests to the `/generate-recipe` endpoint with a JSON body containing `ingredients` and `time`.

### Running the FastAPI Application

To run the FastAPI application, execute the following command:
```
uvicorn AI/src/app2.py:app --host 0.0.0.0 --port 5000 --reload
```
The application will also start on `http://127.0.0.1:5000`, and you can send POST requests to the `/generate-recipe` endpoint with a JSON body containing `ingredients` and `time`.

## API Endpoint

### POST /generate-recipe

**Request Body:**
```json
{
  "ingredients": "ingredient1, ingredient2",
  "time": 30
}
```

**Response:**
- On success:
  ```json
  {
    "recipe": "Generated recipe details here."
  }
  ```
- On error:
  ```json
  {
    "error": "Error message here."
  }
  ```

## License

This project is licensed under the MIT License. See the LICENSE file for more details.