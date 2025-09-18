Registration (Create)
•	A visitor goes to /register.
•	The showRegistrationForm() method shows the registration page.
•	When they submit, the register() method validates:
o	Name must be filled in.
o	Email must be unique.
o	Password must be at least 6 characters and confirmed.
•	If valid:
o	A new User record is created in the database (password is hashed).
o	The new user is logged in automatically.
o	They are redirected to the dashboard with a success message.

Login (Read / Authenticate)
•	A visitor goes to /login.
•	The showLoginForm() method shows the login page.
•	When they submit, the login() method validates the email and password format.
•	If credentials match:
o	Auth::attempt() checks the database.
o	On success, the session is regenerated for security.
o	The user is redirected to the dashboard.
•	If login fails:
o	They are sent back with an error message: "Invalid credentials. Please try again."

Dashboard (Read all users)
•	After login or registration, the user lands on /dashboard.
•	The dashboard() method loads all users from the database and passes them to the dashboard.blade.php.
•	On the dashboard, the user can:
o	View a table of all users.
o	Add a new user (Create).
o	Edit any user (Update).
o	Delete users (Delete).

Add User (Create from Dashboard)
•	The dashboard has a form to create a new user.
•	Submitting this form triggers the store() method.
•	store() validates and saves the new user with a hashed password.
•	The page reloads with a message: "New user created successfully."

Edit User (Update)
•	On the dashboard table, the Edit button links to /users/{id}/edit.
•	The edit($id) method finds the user and loads their details into edit.blade.php.
•	When the form is submitted:
o	The update() method validates inputs.
o	It updates the user’s name and email.
o	If a new password is entered, it hashes and saves it.
•	On success, the dashboard reloads with: "User updated successfully."

Delete User (Delete)
•	On the dashboard, each row has a Delete button.
•	This calls the destroy($id) method.
•	Before deleting:
o	The system checks if the logged-in user is trying to delete themselves (that is blocked).
•	If valid:
o	The user record is removed from the database.
o	Dashboard reloads with: "User deleted successfully."

Logout
•	The logout button submits to /logout.
•	The logout() method:
o	Logs the user out.
o	Invalidates the session.
o	Regenerates the CSRF token.
o	Redirects to /login with: "You have been logged out."

Features
•	Authentication: Secure login and registration.
•	Session Management: Sessions are regenerated on login and cleared on logout.
•	CRUD for Users:
o	Create: Register or add users from dashboard.
o	Read: See all users in dashboard.
o	Update: Edit user details.
o	Delete: Remove users (except yourself).
•	Validation & Security:
o	Input validation for all forms.
o	Passwords hashed using Hash::make().
o	Basic sanitization with strip_tags and filter_var.

