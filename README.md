# React Inertia Laravel Tailwind (RILT) with Mantine UI Multi Tenancy Starter Project

[RILT Starter Template](https://github.com/IsaacHatilima/multi-tenancy)

## Project Status: Active Development

## About The Project

This project is a comprehensive starter template designed to simplify and accelerate the development of modern web
applications. It integrates key technologies like React, Inertia.js, Laravel, and Tailwind CSS. Out of the box the app
provides authentication and multi tenancy ready to go.

### Key Features:

- Clean, organized architecture with actions-based controllers for better maintainability.
- Ready-to-go auth routes and views with **Inertia.js** and **React** for a seamless SPA experience.
- **Tailwind CSS** for responsive, customizable UI design.
- Pre-configured **Mantine UI** for polished, user-friendly interfaces.
- Scalable architecture for adding new features and extending functionality.
- Multi tenancy with multi database configuration.

## Tech Stack:

- **[React](https://react.dev/)**: A powerful JavaScript library for building dynamic user interfaces with a
  component-based architecture.
- **[Inertia.js](https://inertiajs.com/)**: A framework-agnostic tool that allows you to build modern, single-page
  apps (SPAs) using server-side
  routing and controllers.
- **[Laravel](https://laravel.com/)**: A robust PHP framework for building web applications with an elegant syntax.
- **[Tailwind CSS](https://tailwindui.com/)**: A utility-first CSS framework for creating responsive and customizable
  designs.
- **[Mantine UI](https://mantine.dev/)**: A React component library for building beautiful user interfaces with a rich
  set of pre-built
  components.
- **[Tenancy for Laravel](https://tenancyforlaravel.com/)**: A flexible multi-tenancy package for Laravel. Single &
  multi-database tenancy.

## Installation

To get started with the project, follow these steps:

1. Clone the repository:
   ```bash
   git clone https://github.com/IsaacHatilima/multi-tenancy.git
   cd multi-tenancy

2. Install dependencies
    ```bash
    composer install
    npm install

3. Configure your ```.env``` file with the necessary credentials.
4. Run migrations and seed the database:
    ```bash
   php artisan migrate --seed
5. Start the development server:

    ```bash
   php artisan serve
   npm run dev

Visit http://localhost:8000 to see the app in action.

## Architecture Decisions

**Actions-based Controllers:** The project moves business logic into action classes to keep controllers focused on
request handling. This leads to a cleaner, more maintainable structure.

**Inertia.js + React:** Combining Inertia.js with React allows for a seamless SPA experience with minimal client-side
routing and more intuitive server-side controller integration.

**Tailwind + Mantine UI:** Tailwind provides utility-first styling, while Mantine UI offers rich, pre-designed
components that enhance the user experience out of the box. Together, they allow for rapid UI development.

**Multi Database:** With multi databases, this provides an easy and safe way to not only separate tenant data but
also making database backup much easier. 
