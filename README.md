
# User Handler

Management of users


## Authors

- [@qperror](https://www.github.com/qperror)




## 🚀 About Me
Hello! I'm Erro Hasanpor, a 20-year-old senior programmer at Sani Company. I have acquired my associate degree in Software Engineering from Shahid Rajaei Technical Vocational College in Lahijan. With 4 years of professional experience, I have had an exceptional opportunity to work on diverse projects and enhance my skills in web design and development. Throughout this journey, I have actively participated in creating websites for various industries, including insurance, travel, and e-commerce.

I have a strong passion for programming and possess expertise in HTML, CSS, JavaScript, PHP, MySQL, and WordPress. I firmly believe that strong work ethics, meticulous attention to detail, and continuous learning are the key factors for success in the software industry. I am genuinely thrilled about the future and eagerly look forward to making a positive impact in this field.


# UserHandler API Documentation

The UserHandler class provides methods for user authentication, user information retrieval, updating user profiles, and generating authentication tokens.

## Constructor

### `__construct($db)`

Creates a new instance of the UserHandler class.

- Parameters:
  - `$db`: An instance of the database connection class.

## Methods

### `userInfo($token)`

Retrieves user information based on the provided authentication token.

- Parameters:
  - `$token`: The authentication token for the user.

### `loginUser($username, $password)`

Authenticates the user with the provided username and password.

- Parameters:
  - `$username`: The username of the user.
  - `$password`: The password of the user.

### `logoutUser()`

Logs out the currently logged-in user.

### `updateProfile($data)`

Updates the user profile information.

- Parameters:
  - `$data`: An array containing the updated user profile data. The array may include the following optional fields:
    - `token`: The authentication token for the user.
    - `first_name`: The updated first name of the user.
    - `last_name`: The updated last name of the user.
    - `phone`: The updated phone number of the user.
    - `email`: The updated email address of the user.
    - `password`: The updated password of the user.

### `generateToken($userId)`

Generates an authentication token for the provided user ID.

- Parameters:
  - `$userId`: The ID of the user.

### `checkToken($token, $type = "")`

Checks the validity of the provided authentication token.

- Parameters:
  - `$token`: The authentication token to be checked.
  - `$type`: (Optional) The type of the token check. Can be used for specific handling during token checks.

### `updateToken($userId, $token)`

Updates the authentication token for the specified user.

- Parameters:
  - `$userId`: The ID of the user.
  - `$token`: The new authentication token.

### `authenticateUser()`

Authenticates the user based on the provided username and password.

- Returns:
  - `true` if the user is authenticated successfully.
  - `false` if authentication fails.
