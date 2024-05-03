
## Getting Started 🚀

### Installation 🔧

1. Clone the repository to your local machine.
2. Navigate to the project directory.
3. Run `composer install` to install the project dependencies.
4. Create duplicate env.local file.
5. Rename new file with .env
6. Run `./vendor/bin/sail up` to setup docker containers [Sail installation may take several minutes while Sail's application containers are built on your local machine.]
7. Open new terminal tab and run `./vendor/bin/sail artisan migrate` to create tables


That's it. Now you can use the api, i.e.

```
http://127.0.0.1:8000/api/v1/user
http://127.0.0.1:8000/api/v1/resume
```