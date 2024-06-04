# Market Place

Market Place is a web application built with PHP and the Symfony framework that allows users to create, read, update, and delete advertisements (CRUD operations) for various products or services they want to offer.

## Features

- **User Management**: Users can register new accounts and log in/log out. User authentication and authorization mechanisms are implemented using Symfony's security features.
- **Advert Management**: Users can create, read, update, and delete adverts. Each advert contains a title, description, and the user who created it. Additional information such as price, location, and categories can be included.
- **Browsing Adverts**: Anyone can browse existing adverts. The application provides a list view of all advert titles, which may be paginated, and clicking on an advert title displays its full details.
- **Role-based Access Control (Optional)**: Moderators may be allowed to edit or delete any user's adverts, while admins might have the ability to promote or demote moderators and delete user accounts.
- **Additional Features (Optional)**: The ability to upload and display images for adverts.

  ## Install dependencies: composer Install
  ## Configure environment variables:
  ## Update the `.env` file with your database credentials.
  ## Set up the database:
     php bin/console doctrine:migrations:migrate
     php bin/console doctrine:fixtures:load
  ## Start the development server:
    php bin/console server:run
  ## now access the application at `http://localhost:8000`.

## Usage

1. Register a new user account by visiting `http://localhost:8000/register`.
2. Once logged in, you can create new adverts, edit and delete your own adverts, and browse existing adverts created by other users.
3. If additional features like moderator and admin roles are implemented, you can test those functionalities as well.
   
