[![Contributors][contributors-shield]][contributors-url]
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]
[![Issues][issues-shield]][issues-url]
[![MIT License][license-shield]][license-url]

<br />
<div align="center">
  <a href="https://github.com/caranouga/RevisionsCode">
    <img src="images/logo.png" alt="Logo" width="200" height="200">
  </a>

  <h3 align="center">RevisionsCode</h3>

  <p align="center">
    A website to revise the "code de la route"
    <br />
    <br />
    <a href="http://172.252.236.58">View Demo</a>
    ·
    <a href="https://github.com/caranouga/RevisionsCode/issues">Report Bug</a>
    ·
    <a href="https://github.com/caranouga/RevisionsCode/issues">Request Feature</a>
  </p>
</div>

<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
      </ul>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#usage">Usage</a></li>
    <li><a href="#roadmap">Roadmap</a></li>
    <li><a href="#contributing">Contributing</a></li>
    <li><a href="#license">License</a></li>
    <li><a href="#contact">Contact</a></li>
  </ol>
</details>


## About The Project

This project is a website to revise the "code de la route". It's composed of 3 parts:
* The actual website where you can enter your statistics and see your progress
* A chrome extension that allows you to automatically send your statistics to the website
* A TypeScript script that get the questions of "[Passe Ton Code](https://passetoncode.fr)"

<p align="right">(<a href="#top">back to top</a>)</p>


### Built With

This project was built using these technologies.

* [PHP](https://www.php.net/)
* [MySQL](https://www.mysql.com/)
* [HTML](https://html.spec.whatwg.org/)
* [CSS](https://www.w3.org/Style/CSS/Overview.en.html)
* [JavaScript](https://www.javascript.com/)
* [TypeScript](https://www.typescriptlang.org/)

<p align="right">(<a href="#top">back to top</a>)</p>


## Getting Started

To get a local copy up and running follow these simple steps.

### Prerequisites

* npm
  ```sh
  npm install npm@latest -g
  ```
* MySQL
* PHP
* A web server
* A browser

### Installation

1. Clone the repo
   ```sh
   git clone https://github.com/caranouga/RevisionsCode.git
   ```
2. Create the database
   ```sh
    mysql -u root -p < init.sql
    ```
3. Start the web server
4. Open the website in your browser
5. Enjoy!

<p align="right">(<a href="#top">back to top</a>)</p>


## Usage

You can use this project to revise the "code de la route". You can also use the chrome extension to automatically send your statistics to the website. Or you can use the TypeScript script to get the questions of "[Passe Ton Code](https://passetoncode.fr)" (SOON).

<p align="right">(<a href="#top">back to top</a>)</p>



## Roadmap

- [x] Create the website
- [x] Create the chrome extension
- [ ] Make the extension work with Firefox
- [ ] Implement the questions gather thanks to the TypeScript script in the website
- [ ] Update the init.sql

See the [open issues](https://github.com/caranouga/RevisionsCode/issues) for a full list of proposed features (and known issues).

<p align="right">(<a href="#top">back to top</a>)</p>


## Contributing

Contributions are what make the open source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

If you have a suggestion that would make this better, please fork the repo and create a pull request. You can also simply open an issue with the tag "enhancement".
Don't forget to give the project a star! Thanks again!

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

<p align="right">(<a href="#top">back to top</a>)</p>


## License

Distributed under the [repo-licence] License. See `LICENSE.txt` for more information.

<p align="right">(<a href="#top">back to top</a>)</p>


## Contact

Caranouga - malonepayet@gmail.com

Project Link: [https://github.com/caranouga/RevisionsCode](https://github.com/caranouga/RevisionsCode)

<p align="right">(<a href="#top">back to top</a>)</p>


[contributors-shield]: https://img.shields.io/github/contributors/caranouga/RevisionsCode.svg?style=for-the-badge
[contributors-url]: https://github.com/caranouga/RevisionsCode/graphs/contributors
[forks-shield]: https://img.shields.io/github/forks/caranouga/RevisionsCode.svg?style=for-the-badge
[forks-url]: https://github.com/caranouga/RevisionsCode/network/members
[stars-shield]: https://img.shields.io/github/stars/caranouga/RevisionsCode.svg?style=for-the-badge
[stars-url]: https://github.com/caranouga/RevisionsCode/stargazers
[issues-shield]: https://img.shields.io/github/issues/caranouga/RevisionsCode.svg?style=for-the-badge
[issues-url]: https://github.com/caranouga/RevisionsCode/issues
[license-shield]: https://img.shields.io/github/license/caranouga/RevisionsCode.svg?style=for-the-badge
[license-url]: https://github.com/caranouga/RevisionsCode/blob/master/LICENSE.txt
[product-screenshot]: images/screenshot.png