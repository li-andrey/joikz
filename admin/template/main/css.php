<link rel="shortcut icon" href="<?= ASSETS ?>images/favicon.png" type="image/png">
<link rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'" href="https://use.fontawesome.com/7be2c69d98.css">
<link rel="stylesheet" href="<?= ASSETS ?>css/bootstrap.min.css">
<link rel="stylesheet" href="<?= ASSETS ?>css/swiper-bundle.min.css" />
<link rel="stylesheet" type="text/css" href="<?= ASSETS ?>css/slick.css" />
<link rel="stylesheet" type="text/css" href="<?= ASSETS ?>css/slick-theme.css" />
<link rel="stylesheet" href="<?= ASSETS ?>css/style.css?v=3.85">
<style type="text/css">
    .navbar-brand {
        width: 61px;
        background-size: 82px !important;
    }

    .page-link:hover {
        z-index: 2;
        color: #2e2e2e;
        text-decoration: none;
        background-color: #e9ecef;
        border-color: #dee2e6;
    }

    .page-item.active .page-link {
        z-index: 3;
        color: #fff;
        background-color: #2e2e2e;
        border-color: #2e2e2e;
    }

    .product .gallery .slider-nav .slick-slide.slick-current .image {
        background: #e1e1e1;
    }

    .product .colors .item {
        cursor: pointer;
    }

    .product .sizes .item {
        cursor: pointer;
    }

    .product .sizes .item.disabled {
        cursor: default;
    }

    .login1 .modal-content {
        width: 100%;
        padding: 30px;
        box-shadow: none;
        border: 3px solid #fafafa;
        background: #fff;
    }

    .modal-content h3 {
        margin: 0px 0px 20px;
        font-size: 1rem;
        font-weight: bold;
    }

    .login1 .btn {
        margin-bottom: 1rem;
        margin-left: 0.5rem;
        margin-right: 0.5rem;
        white-space: nowrap;
        font-size: 0.85rem;
    }

    .login1 .dialog-footer {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
    }

    .alert {
        text-align: center;
    }

    .cart a {
        text-decoration: none;
    }

    .like-icon.active svg {
        fill: #ce0000;
    }

    .btn-outline-primary:not(:disabled):not(.disabled).active,
    .btn-outline-primary:not(:disabled):not(.disabled):active,
    .show>.btn-outline-primary.dropdown-toggle {
        color: #000;
        background-color: #fff;
        border-color: #000;
    }

    .additional>li>a {
        display: flex;
        align-items: center;
    }


    .product-item {
        max-width: 100%;
        width: 100%;
        min-width: unset;
    }

    .d-xl-block {
        display: none !important;
    }

    .product .tabs {
        width: 100%;
    }

    .product .gallery .slick-prev,
    .product .gallery .slick-next {
        background: #fff;
        color: #2e2e2e;
    }

    .product .gallery .slick-prev:hover,
    .product .gallery .slick-next:hover {
        background: #2e2e2e;
        color: #fff;
    }



    .product .gallery .slick-prev:before,
    .product .gallery .slick-next:before {
        color: #2e2e2e;
    }

    .product .gallery .slick-prev:hover:before,
    .product .gallery .slick-next:hover:before {
        color: #fff;
    }

    @media screen and (min-width: 1200px) {
        .col-xl {
            -ms-flex-preferred-size: 0;
            flex-basis: 0;
            -ms-flex-positive: 1;
            flex-grow: 1;
            max-width: 100%;
        }

        .col-xl {
            max-width: 20%;
        }
    }

    @media screen and (min-width: 1440px) {
        .d-xl-block {
            display: block !important;
        }

        .col-xl {
            max-width: 20%;
        }
    }

    .content.order .card {
        border: 0px;
    }

    .content.order h2 {
        font-family: 'Halvar Breit Md';
        height: 45px;
        line-height: 50px;
        display: block;
        padding: 0 0rem;
        font-weight: 500;
        transition: all 0.3s ease;
        position: relative;
        font-size: 16px;
        border: 0px solid #000;
        margin: 0px;
    }


    .content.order .form-control {
        border: 1px solid #717171;
        border-radius: 0px;
    }



    @media screen and (max-width: 767px) {
        .badge-dark {
            font-size: 9px;
            padding: 0.3rem 0.25rem;
        }

        .badge-light {
            font-size: 9px;
            padding: 0.3rem 0.25rem;
        }

        .product-item {
            min-width: 0px;
            max-width: unset;
            margin: 0px -14px 2px;
            width: auto;
        }

        .product .gallery .slick-prev,
        .product .gallery .slick-next {
            width: 1.5rem;
        }

        .product .gallery .slick-slider.slider-for {
            padding: 0px 1.5rem;
        }

        .product .gallery {
            margin-bottom: 2rem;
        }

        .product .title {
            font-size: 1rem;
        }

        .product .price {
            font-size: 1rem;
            font-family: 'Halvar Breit Md';
        }

        .product .pt-5 {
            padding-top: 1rem !important;
        }

        .about-ul>li>a {
            height: 45px;
            line-height: 45px;
        }

        .about-ul>li>a:after {
            height: 45px;
            line-height: 45px;
            right: 1rem;
        }

        .product {
            font-size: 13px;
        }

        .product .buttons .btn {
            text-transform: uppercase;
            font-weight: bold;
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
        }

        .product .buttons .btn.btn-outline-primary {
            padding-left: 0.25rem;
            padding-right: 0.25rem;
            padding-top: 0.32rem;
            padding-bottom: 0.32rem;
        }

        .about-ul .info p {
            font-size: 13px;
        }

        .about-ul .info {
            font-size: 13px;
        }

        .product-grid.py-5 {
            padding: 1rem 0 !important
        }

        .pagination {
            margin-top: -2rem;
        }

        .cart .price {
            font-size: 12px;
            font-weight: bold;
            color: #000;
        }

        .cart .name {
            color: #717171;
            font-size: 10px;
            font-family: 'Halvar Breit Md';
        }

        .cart .name p {
            margin-bottom: 0px;
        }

        .cart .art {
            font-size: 10px;
            color: #000;
        }

        .product.py-5 {
            padding-bottom: 0px !important;
        }

        .info.mt-3 {
            margin-top: 0.5rem !important;
        }

        .content.order .form-control {
            margin-bottom: 1rem;
        }

        .order-block.p-3,
        .order-block.px-3 {
            padding-left: 0px !important;
            padding-right: 0px !important;
        }

        .product-item .info {
            padding-left: 8px;
            padding-right: 8px;
        }

        .product-item .name {
            margin-left: -10px;
            margin-right: -10px;
        }

        .cart .count {
            justify-content: center;
        }

        .cart .count input {
            font-size: 1rem;
        }

        .cart .count a {
            font-size: 1rem;
        }
    }

    .product .sizes {
        border: 1px solid #fff;
        padding: 0.25rem;
        position: relative;
    }

    .product .sizes.error {
        border: 1px solid red;
        margin-bottom: 2rem;

    }

    .product .sizes.error:after {
        content: 'Выберите размер';
        display: block;
        width: 100%;
        color: red;
        text-align: center;
        padding: 5px 0;
        position: absolute;
        bottom: -35px;
        left: 0px;
    }

    .cart .price {
        font-size: 1rem;
        font-weight: bold;
        color: #ff2a00;
    }

    .cart .price s {}

    .cart .price s {
        font-size: 0.75rem;
        color: #000;
        position: relative;
        font-weight: normal;
        text-decoration: none;
    }

    .product-item .price s {
        font-size: 0.75rem;
        color: #000;
        position: relative;
        font-weight: normal;
        text-decoration: none;
    }

    .product-item .price {
        color: #ff2a00;
    }

    .product-item .price s:after {
        content: "";
        display: block;
        position: absolute;
        left: 0;
        top: 50%;
        color: #717171;
        width: 100%;
        height: 0;
        border-bottom: 1px solid #000;
        transform: rotate(8deg);
    }

    .cart .price s:after {
        content: "";
        display: block;
        position: absolute;
        left: 0;
        top: 50%;
        color: #717171;
        width: 100%;
        height: 0;
        border-bottom: 1px solid #000;
        transform: rotate(8deg);
    }

    .like-icon {
        height: 22px;
        width: 20px;
        background: url(/upload/bookmark.svg) no-repeat center center;
        display: inline-block;
        background-size: cover;
    }

    .like-icon.active {
        background: url(/upload/bookmark-fill.svg) no-repeat center center/cover;
    }

    @media (hover: hover) and (pointer: fine) {
        .like-icon:hover {
            background: url(/upload/bookmark-fill.svg) no-repeat center center/cover;
        }
    }

    .product .art {
        display: none;
        color: #717171;
    }

    .badges .badge-dark {
        display: none;
    }

    .badges .badge-dark.new {
        float: left !important;
        display: block;
    }

    .badge-light {
        background-color: #ff2a00;
        color: #fff;
    }

    .slick-dotted.slick-slider {
        margin-bottom: 0px;
    }

    .desktop ._3ObZ8tOCxqxwOAOwJpU3Ll:hover {
        background-color: #fffc;
    }

    .slick-dots {
        position: static;
        bottom: 0px;
        display: block;
        width: auto;
        padding: 0;
        margin: 0;
        list-style: none;
        text-align: center;
    }

    .my-dots {
        background: #fff;
        padding: 5px 5px;
        position: absolute;
        left: 50%;
        bottom: 0px;
        transform: translateX(-50%);
        margin: 0px;
        width: auto;
    }

    .product .gallery {
        position: relative;
    }

    .slick-dots li {
        position: relative;
        display: inline-block;
        width: 10px;
        height: 10px;
        margin: 0 5px;
        padding: 0;
        cursor: pointer;
    }

    .slick-dots li button {
        font-size: 0;
        line-height: 0;
        display: block;
        width: 10px;
        height: 10px;
        padding: 0px;
    }

    .slick-dots li button:before {
        font-family: 'slick';
        font-size: 6px;
        line-height: 3px;
        position: absolute;
        top: 0;
        left: 0;
        width: 10px;
        height: 10px;
        content: '•';
        text-align: center;
        opacity: .25;
        color: black;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    .slick-dots li.slick-active button:before,
    .slick-dots li:hover button:before {
        opacity: 1;
        color: #000;
        font-size: 9px;
        line-height: 5px
    }

    .my-dots a {
        padding: 5px 5px;
        opacity: 0.5;
        margin: 0px 5px;
        display: block;
    }

    .my-dots a:hover {
        opacity: 1;
    }

    .my-dots a img {
        width: auto;
    }

    @media screen and (min-width: 768px) {
        header .nav-item {
            padding: 0 1rem;
            position: relative;
        }

        .border-top-lg-1 {
            border-top: 1px solid #000 !important;
        }
    }

    .btn-link,
    .btn-link {
        color: #000;
    }

    .btn-link:hover,
    .btn-link:hover {
        color: #000;
    }

    .btn-link.disabled,
    .btn-link:disabled {
        color: #6c757d;
    }

    @media screen and (max-width: 767px) {
        .my-dots a {
            display: none
        }

        .order-block b {
            white-space: nowrap;
        }

        header .icons ul li a {
            width: 22px !important;
            background-size: contain;
        }

        header .icons ul li a.person {
            width: 26px !important;
            background-size: contain;
        }

        header .icons ul li .nav-item a {
            width: 100% !important;
            background-size: contain;
            font-size: 14px;
            padding-left: 5px !important;
            padding-right: 5px !important;
        }

        .cart .count input {
            font-size: 1.5rem;
        }

        .cart .count a {
            font-size: 1.5rem;
        }

        .cart .count a.minus {
            font-size: 2rem;
        }

        #basket_place_order {
            padding: 10px;
            background: #F2F2F2;
        }

        .basket-order-info hr {
            margin-top: 1.5rem;
            margin-bottom: 1.5rem;
        }
    }

    .cart .art {
        color: #717171;
        font-size: 0.85rem;
        font-family: 'Halvar Breit Md';
    }

    .product .price s:after {
        transform: none;
    }

    .product-item .price s:after {
        transform: none;
    }

    .cart .price s:after {
        transform: none;
    }

    .content .container {
        max-width: 1280px;
        padding-left: 20px;
        padding-right: 20px;
        margin: auto;
    }

    .navbar .container {
        max-width: 1280px;
        padding-left: 20px;
        padding-right: 20px;
        margin: auto;
    }

    .filter>li>a {
        max-width: 1280px;
        padding-left: 20px;
        padding-right: 20px;
        margin: auto;
    }

    .filter .info {
        max-width: 1280px;
        padding-left: 20px;
        padding-right: 20px;
        margin: auto;
    }

    footer .menu li>a {
        max-width: 1280px;
        padding-left: 20px;
        padding-right: 20px;
        margin: auto;
    }

    footer .menu .info {
        max-width: 1280px;
        padding-left: 20px;
        padding-right: 20px;
        margin: auto;
    }

    .filter>li>a:before {
        content: '';
        width: 1000px;
        background: #fff;
        position: absolute;
        right: 100%;
        top: 0px;
        height: 100%;
        transition: all 0.3s ease;
    }


    .filter>li>a:after {
        content: '+';
        width: 1000px;
        background: #fff;
        position: absolute;
        left: 100%;
        top: 0px;
        height: 100%;
        transition: all 0.3s ease;
        text-indent: -34px;
    }

    .filter>li>a:hover:before,
    .filter>li>a:hover:after {
        background: #0000001a;
    }

    .filter>li {
        overflow: hidden;
    }

    footer .menu li>a:before {
        content: '';
        width: 1000px;
        background: #fff;
        position: absolute;
        right: 100%;
        top: 0px;
        height: 100%;
        transition: all 0.3s ease;
    }


    footer .menu li>a:after {
        content: '+';
        width: 1000px;
        background: #fff;
        position: absolute;
        left: 100%;
        top: 0px;
        height: 100%;
        transition: all 0.3s ease;
        text-indent: -34px;
    }

    footer .menu li>a:hover:before,
    footer .menu li>a:hover:after {
        background: #0000001a;
    }

    footer .menu li {
        overflow: hidden;
    }

    .badges {
        font-size: 10px;
    }

    .badges>div {
        padding: 0.2rem 0.5rem 0.15rem;
    }

    .cart .count input {
        outline: 0px !important;
        font-size: 1.5rem;
    }

    .cart .count a {
        font-size: 1.75rem;
    }

    .cart .count a.plus {
        font-size: 1.5rem;
    }

    .content.order h3 {
        font-family: 'Halvar Breit Md';
        font-weight: 500;
        transition: all 0.3s ease;
        position: relative;
        font-size: 16px;
        border: 0px solid #000;
        margin: 0px;
    }

    #sidebar .card-block {
        position: -webkit-sticky;
        position: sticky;
        top: 6rem;
    }

    @media screen and (min-width: 1200px) {
        .product-item {
            max-width: 224px;
            width: 100%;
            min-width: 210px;
        }
    }

    .login1 .btn {
        width: 200px;
        text-align: center;
    }

    .login1 .modal-content {
        width: 100%;
        padding: 30px 30px 15px;
    }

    @media screen and (min-width: 1024px) {
        .n-search {
            width: 95px;
        }

        .n-search__form {
            width: 95px;
        }
    }

    @media screen and (min-width: 1200px) {
        .n-search {
            width: 181px;
        }

        .n-search__form {
            width: 181px;
        }
    }

    @media screen and (max-width: 1024px) {
        .navbar-toggler {
            padding: 0.25rem 0 0.25rem 0.75rem;
        }

        .navbar-toggler {
            color: #333;
            font-size: 24px;
            z-index: 10
        }

        .navbar-expand-md .navbar-toggler {
            display: block;
        }

        .navbar .icons {
            margin-left: auto;
            margin-right: 20px;
        }

        .navbar-collapse {
            position: fixed;
            top: 57px;
            width: 100%;
            z-index: 100;
            background: #fff;
            left: 0px;
            padding: 25px 25px 95px;
            height: 100% !important;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            overflow-y: scroll;
        }

        .navbar-nav {
            width: 100%
        }

        header .nav-item span {
            position: absolute;
            right: 0px;
            top: 15px;
            transform: translateY(-50%);
            color: #000;
            font-size: 20px;
            z-index: 30;
            width: 30px;
            height: 30px;
            text-align: center;
        }

        header .nav-item ul {
            position: static;
            box-shadow: none;
            text-align: left;
            align-items: flex-start;
            display: none;
            padding: 0px;
            width: 100%
        }

        header .nav-item:hover ul {
            display: block;
            visibility: visible;
            opacity: 1;
            transform: translateY(0%);
            transition: all .3s ease-in-out;
        }

        header .nav-item ul {
            width: 100%
        }

        header .icons ul {
            position: relative;
            z-index: 10
        }

        .search-on .n-search__top {
            z-index: 12
        }

        .product-item {
            max-width: 100%;
            width: 100%;
        }

        .product-item .price {
            font-size: 0.75rem;
        }

        .product-item .brand {
            font-size: 0.85rem;
        }

        .product .gallery {
            margin-bottom: 2rem;
        }

        .product .buttons .btn {
            text-transform: uppercase;
            font-weight: bold;
            padding: 0.5rem 2rem;
        }

        .filter-subject i {
            position: absolute;
            right: 12px;
            top: 16px;
            font-size: 20px;
        }

        .filter-box {
            border: 0px;
            border-bottom: 1px solid #e0e0e0;
            height: auto;
            overflow: auto;
        }

        .filter-box>div {
            display: none;
        }

        .filter-box>div {
            display: none;
        }



        .filter-box>div.filter-subject {
            display: block;
            padding: 1rem 0
        }

        .filter-box.active>div {
            display: block;
        }

        .filter-box.active i {
            transform: rotate(180deg);
        }
    }

    .product .sizes .item {
        min-width: 2.5rem;
        width: auto;
        padding-left: 3px;
        padding-right: 3px;
        margin-bottom: 0.5rem
    }

    .product .sizes {
        flex-wrap: wrap;
    }

    .btn-primary.disabled,
    .btn-primary:disabled {
        color: #fff;
        background-color: #000;
        border-color: #000;
        opacity: 0.5;
    }

    @media screen and (max-width: 767px) {
        .product-grid .row {
            margin: 0px -10px;
        }

        .product-grid .row .col-6 {
            padding: 0px 5px;
        }

        .product-item .brand {
            font-size: 0.75rem;
        }

        .product-item {
            margin: 0px 0px 2px;
        }

        .navbar .container {
            max-width: 1280px;
            padding-left: 0px;
            padding-right: 0px;
            margin: auto;
        }

        .product-item .brand {
            margin-left: -10px;
            margin-right: -10px;
        }
    }
</style>