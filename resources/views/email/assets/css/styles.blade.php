html {
    width: 100%;
}

body {
    font-family: "Roboto", Helvetica, Arial, sans-serif;
    font-weight: 100;
    width: 100% !important;
    margin: 0;
    padding: 0;
    color: #30363d;
    background-color: #cddade;
    -webkit-text-size-adjust: 100%;
    -ms-text-size-adjust: 100%;
}

body>table {
    background-color: #eeeeee;
}

img {
    display: block!important;
    width: 100%!important;
    max-width: 600px !important;
    height: auto!important;
    margin: 0;
    padding: 0;
    text-decoration: none;
    border: 0;
    outline: none;
}

h1,
h2,
h3,
h4,
h5,
h6 {
    font-family: "Roboto", Helvetica, Arial, sans-serif;
    font-weight: bold;
    color: #30363d;
}

p,
ul,
ol,
small {
    font-family: "Roboto", Helvetica, Arial, sans-serif;
    font-weight: 100;
    color: #30363d;
}

a {
    color: #006dc7;
}

table td {
    border-collapse: collapse;
}

table {
    table-layout: fixed;
}

table table table {
    table-layout: auto;
}

.full_width,
.full_wrapper {
    max-width: 600px;
}

.half_width_table {
    max-width: 300px;
    padding: 20px;
}

.full_width_text {
    padding: 15px 20px 25px 20px;
    background-color: #ffffff!important;
}

.full_width_image {
    max-width: 600px;
    margin: 0!important;
    padding: 0!important;
    border: 0;
}

.table_col {
    padding: 5px;
}

.table_right {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #DDD;
}

.table_right .table_col {
    padding: 10px;
}

.btn {
    font-size: 16px;
    font-weight: 100;
    line-height: 44px;
    display: inline-block;
    width: 200px;
    text-align: center;
    text-decoration: none;
    color: #fff;
    border: 0;
    border-radius: 2px;
    background-color: #0a3444;
    -webkit-text-size-adjust: none;
    mso-hide: all;
}

.btn-col {
    width: 100px!important;
    margin: 0;
    padding: 0;
    border: 0;
}

.disclaimer {
    padding: 30px 20px 20px 20px;
    color: #006dc7;
    text-align: left;
    color: white;
}

.disclaimer h5 {
    font-size: 14px;
    font-weight: 100;
    margin: 0;
    margin-bottom: 20px;
    padding: 0;
    color: #30363d;
    text-align: left;
}

.disclaimer p {
    font-size: 11px;
    font-weight: 100;
    margin: 0;
    margin-bottom: 10px;
    padding: 0;
    color: #a9adb7;
    text-align: left;
}

.text-left {
    text-align: left;
}

@media only screen and (max-width: 700px) {
    table[class="full_width"],
    table[class="table-inner"] {
        width: 96% !important;
        max-width: 600px!important;
    }
    table[class="full_wrapper"] {
        width: 100% !important;
        max-width: 600px!important;
    }
}

@media only screen and (max-width: 599px) {
    table[class="table-full"],
    table[class="product-outer"] {
        width: 96% !important;
        margin: 0 2% 0 2%!important;
    }
    *[class="mb_hide"] {
        display: none !important;
    }
}