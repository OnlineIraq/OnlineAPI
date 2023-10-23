<template>
  <router-view></router-view>
</template>

<style lang="scss" >
.right .modal-header .close,
#right .modal-header .close {
  padding: 1rem 1rem !important;
  margin: -1rem 0rem -1rem -1rem !important;
}

body {
  margin: 0px !important;
  padding: 0px !important;
}

#right,
.right {
  direction: rtl;
}

#right *,
.right * {
  text-align: right;
}

#left,
.left {
  direction: ltr;
}

#left *,
.left * {
  text-align: left;
}

.right .owl-carousel,
.bx-wrapper {
  direction: ltr !important;
}
.right .owl-carousel .owl-item {
  direction: rtl !important;
}

table img {
  width: 80px;
  height: 50px;
  cursor: pointer;
}

.modal img {
  width: 100px;
  height: 100px;
}

.left .modal img {
  float: left;
}

.right .modal img {
  direction: rtl;
  float: right !important;
}

// form input css


.form-input input {
  padding: 10px 0;
  margin-bottom: 30px;
  transition: 0.5s;
}

.form-input textarea {
  height: 80px;
  padding: 10px 0;
  margin-bottom: 40px;
}

.form-input input,
.form-input textarea {
  width: 100%;
  box-shadow: none;
  outline: none;
  border: none;
  box-sizing: border-box;
  border-bottom: 2px solid #999;
  background-color: transparent;
}

.form-input textarea {
  margin-bottom: 20px;
}

.form-input {
  width: 100%;
  position: relative;
}

.form-input label {
  position: absolute;
  top: 10px;
  color: #999;
  transition: 0.5s;
}

#left .form-input label,
.left .form-input label {
  left: 0px !important;
}

#right .form-input label,
.right .form-input label {
  right: 0px !important;
}

.form-input input:focus ~ label,
.form-input textarea:focus ~ label,
.form-input input:valid ~ label,
.form-input textarea:valid ~ label {
  top: -12px;
  color: #3498db;
  font-size: 12px;
  font-weight: bold;
}

#left .form-input input:focus ~ label,
#left .form-input textarea:focus ~ label,
#left .form-input input:valid ~ label,
#left .form-input textarea:valid ~ label,
.left .form-input input:focus ~ label,
.left .form-input textarea:focus ~ label,
.left .form-input input:valid ~ label,
.left .form-input textarea:valid ~ label {
  left: 0px;
}

#right .form-input input:focus ~ label,
#right .form-input textarea:focus ~ label,
#right .form-input input:valid ~ label,
#right .form-input textarea:valid ~ label,
.right .form-input input:focus ~ label,
.right .form-input textarea:focus ~ label,
.right .form-input input:valid ~ label,
.right .form-input textarea:valid ~ label {
  right: 0px;
}

.form-input input:focus,
.form-input textarea:focus,
.form-input input:valid,
.form-input textarea:valid {
  border-bottom: 2px solid #3498db;
  transform-origin: left;
}

.form-input .error {
  position: relative;
  top: -25px;
}

#left .form-input .error,
.left .form-input .error {
  left: 0px;
}

#right .form-input .error,
.right .form-input .error {
  right: 0px;
}

.material-select {
  -webkit-appearance: none !important;
  -moz-appearance: none !important;
  -ms-appearance: none !important;
  outline: none !important;
  appearance: none !important;
  width: 100%;
  color: #444 !important;
  border: none !important;
  outline: none !important;
  border-bottom: 2px solid #999 !important;
  margin-top: 0px;
  padding: 5px 5px;
  -webkit-box-shadow: none !important;
  box-shadow: none !important;
}

.h-45 {
  height: 45px !important;
}

#label-file {
  position: relative;
  color: #666;
  border-bottom: 2px solid #999 !important;
  font-size: 16px;
  font-weight: 400;
  width: 100%;
  margin-top: 15px;
  margin-bottom: 20px;
}

#label-file i {
  color: #666;
  font-size: 22px;
}

#label-file input {
  opacity: 0;
  display: none;
}

.file_input > span {
  position: relative;
  top: -12px;
}
</style>
