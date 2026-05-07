# Laravel Waiting Room 🎟️ 

LaravelWaitingRoom is a waiting room system built with **Laravel**.

## Features 🧩

- **OnlineLimitCheckerMiddleware**: This middleware checks whether the number of online users has reached the limit (defined in `WAITING_ROOM_MAX_ONLINE_USERS` inside the `env` file). If the limit has been reached, the user will be redirected to the `/queue` page, where they will remain until a slot becomes available in the online pages. If the limit has not been reached, the user will be redirected to the requested page.

- **KeepAlive API**: To keep the user online, you need to create a repeating task and send a request to the server at the interval defined by the server. This can easily be done using React's `useEffect` function on the desired page, together with the `tryToStartKeepAlive` function from `resources/js/services/user-limit-service.tsx`, which starts the repeating task responsible for sending a signal to the server indicating that the user is still **online**.

By default, the `/dashboard` page already comes with the full structure ready, so you can use it as an example.

## Installation 🛠️

## Git 🐙

First, you need to clone the repository using **git**. Use the command below:

```sh
git clone https://github.com/RajadorDev/LaravelWaitingRoom.git
```

After that, open the folder where the repository was created:

```sh
cd LaravelWaitingRoom
```

Now you can **build** the app on your machine:

```sh
composer run build
```

## Running the System ⚙️

- Docker Deploy 🐳

To **deploy** the system using Docker, simply run the command below:

```sh
docker-compose up --build
```

And that's it — the system will install `nginx`, `php8.3`, `redis`, and start the `scheduler` responsible for maintaining the **keep alive** and the **queue heartbeat**.

- Development 🧪

**Warning: 🚨**: You need to have Docker installed on your machine so it can install `Redis`, since it is also required for development!

For a development environment, you can use the command:

```sh
php artisan serve
```

And to run the Laravel `scheduler`, you will need to open another terminal instance and execute:

```sh
php artisan schedule:work
```