# MovieStreamingApp

A full-stack movie tracking and review web application built using PHP, MySQL, React and TypeScript. The project demonstrates complete end-to-end development, from secure authentication and RESTful API design to responsive user interface development using modern web technologies.

## Overview

MovieStreamingApp project consists of two major components:
1. **Backend:** A RESTful API built using PHP and MySQL for managing movies, user accounts, and personalized watchlists.
2. **Frontend:** A React + TypeScript single-page application that consumes the backend API and provides an interactive user experience.

## Features

* **Movie Data & User Lists**
  * View and search movies with details, genres, and ratings.
  * Maintain personal To-Watch and Completed-Watch lists.
  * Add, update, or delete movies from lists.
  * Update ratings, notes, priority, and times watched.
  * Automatically recalculates average movie ratings when users update their reviews.
* **User Management & Authentication**
  * **Account Creation:** Register with validation for unique username,  valid email, and password strength.
  * **Login System:** Secure session-based authentication in PHP.
  * **API Key Management:** Each account is issued a unique API key for authorized access.
  * **Account View:** Displays user information and allows regenerating the API key.
* **RESTful API (Backend)**
  * Built using PHP with structured routing and validation.
  * Follows REST conventions with appropriate HTTP methods and status codes.
  * Includes endpoints for:
    * **Movies:** /movies, /movies/{id}, /movies/{id}/rating
    * **To-Watch List:** CRUD endpoints for managing pending movies
    * **Completed-Watch List:** Endpoints for rating, tracking watch count, and stats
    * **User Stats:** /users/{id}/stats for personal insights
    * **Authentication:** /users/session for credential verification and API key retrieval
  * Supports filtering by movie title, genre, rating, and priority.
* **Front-end (React + TypeScript + Vite)**
  * Built using Vite for fast development and modern build performance.
  * Uses React Router for seamless navigation.
  * Integrates with the PHP API via secure fetch requests and API keys.
  * Reusable components, organized context management, and clean styling.
  * Fully responsive layout designed for accessibility and usability.
  * Testing screenshots are included under /Frontend/testing-screenshots/.

## Project Structure

* **MovieStreamingApp**
  * **Backend:** PHP and MySQL-based REST API
    * **api:** Contains all API endpoint handlers
      * **auth.php:** Handles user authentication and API key validation
      * **completedwatchlist.php:** Endpoints for completed movie list management
      * **index.php:** Main router for API requests
      * **movies.php:** Endpoints for retrieving and filtering movie data
      * **towatchlist.php:** Endpoints for to-watch list operations
      * **users.php:** Endpoints for user statistics and data
    * **styles:** Contains styling for PHP-based pages
      * **main.css:** The main CSS stylesheet
    * **create-account.php:** User registration page with form validation
    * **login.php:** Login page that initiates user sessions
    * **view-account.php:** Displays user profile and API key management
    * **index.php:** API documentation and route overview page
    * **database.sql:** Database schema with tables and relationships
  * **Frontend** React + TypeScript front-end application
    * **assets:** Static assets such as images and icons
    * **assn3-Nitya-star-build:** Main React app source folder
      * **src:** React source files
        * **assets:** Component specific images and media
        * **components:** Reusable UI components
          * **CompletedList.tsx:** Displays list of movies user has completed watching
          * **LoginForm.tsx:** Displays the login page to the user
          * **MovieCard.tsx:** Movie display card component for all the lists
          * **MovieCatalogue.tsx:** Displays list of movies fetched from API
          * **MovieDetail.tsx:** Displays the details of the particular movie
          * **SearchBar.tsx:** User can navigate movies easily
          * **WatchList.tsx:** Displays list of movies user wants to watch
        * **context:** React context for global state management
          * **AuthContext.tsx:** Handles authentication and user state
          * **MovieContext.tsx:** Manages movie and list-related data
        * **styles:** CSS modules and global styling
        * **types:** TypeScript interfaces and type definitions
    * **testing-screenshots:** Screenshots for front-end testing and demo proofs
    * **App.tsx:** Root React component defining main app structure
    * **App.css:** Styling for global React components
    * **index.css:** CSS reset and global theme rules
    * **main.tsx:** React entry point file with router and root rendering
  * **README.md:** Documentation describing full-stack project setup in detail

## Technical Skills Demonstrated

* **Backend Development:** PHP, RESTful APIs, JSON processing, authentication, session management
* **Frontend Development:** React, TypeScript, Vite, component-based design, routing
* **Database Management:** MySQL, relational design, data normalization, foreign keys
* **API Integration:** Secure communication between React and PHP backend
* **Accessibility & Semantics:** WCAG concepts, keyboard navigation, semantic HTML
* **Version Control:** Git and GitHub with structured commits and clear documentation
* **Testing:** ThunderClient for API endpoints, UI testing with screenshots
* **Full-Stack Development:** Integration of independent front-end and back-end systems

## Purpose

This project was completed as part of COIS 3430 - Web Development: Backend at Trent University. It combines two assignments to create a full-stack movie streaming and review platform. The project demonstrates the ability to design, implement, and integrate a custom RESTful backend API with a modern React + TypeScript frontend, showcasing strong skills in both server-side and client-side web development aligned with industry standards.

