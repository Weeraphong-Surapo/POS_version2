var app = new Vue({
  el: "#app",
  data: {
    path: "controller/salepicController.php",
    AllProdcut: [],
    AllCategory: [],
    cartProducts: [],
    logCart: [],
    search_img:[],
    logGetMo:0,
    logvatsum:0,
    logChange:0,
    arrayimg:[],
    listCustomer:[],
    check: false,
    getMoney: 0,
    checkvat: false,
    vat: 7,
    totalVat: 0,
    changeMoneys: 0,
    totalsumVat: 0,
    result_price:0,
    print_id: "",
    custoemr:"",
    cus_id:"",
    searchQuery:"",
    searchCategory: "",
    searchValue: "",
    typeprice:"product_price",
    currentPage: 1,
    pageSize: 16,
    check_find: 0,
    currentValue: "",
    error: "",
    seletePay: "",
  },
  methods: {
    clearCustomer(){
      this.cus_id = "";
      this.custoemr = "";
    },
    selectCustomer(fname,lname,id) {
      this.cus_id = id
      this.custoemr = fname+' '+lname;
      $('#ModalCustomer').modal('hide')
  },
    closeModal() {
      $("#ModalSalepic").modal("hide");
    },
    openCustomer(){
        $('#ModalCustomer').modal('show')
    },
    printBill() {
      const pdfUrl = "controller/billtax.php?id="+this.print_id;
      const pdfWindow = window.open(pdfUrl, "_blank");
      pdfWindow.print();
    },
    printBillFull() {
      const pdfUrl = "controller/billfull.php?id="+this.print_id;
      const pdfWindow = window.open(pdfUrl, "_blank");
      pdfWindow.print();
    },
    saleOrder() {
      if (app.checkvat) {
        app.totalVat = (app.totalPriceProduct * app.vat) / 100;
        app.totalsumVat = app.totalPriceProduct + app.totalVat;
      } else {
        app.totalVat = 0;
        app.totalsumVat = app.totalPriceProduct + app.totalVat;
      }
      if (app.currentValue < app.totalPriceProduct) {
        this.swalAlert("warning", "กรุณารับเงินให้เท่ากับหรือมากกว่าราคาขาย");
      }else if((app.currentValue - app.totalsumVat)  > 1000){
        this.swalAlert("warning", "เงินทอนไม่ควรเกิน 1,000 บาท");
      } else {
        app.changeMoneys = app.currentValue - app.totalsumVat;
        app.logChange = app.changeMoneys
        app.logvatsum = app.totalsumVat
        axios
          .post(this.path, {
            action: "SaleProduct",
            Cart: app.cartProducts,

            countcart: app.cartProducts.length,
            vatproduct: app.totalVat,
            totelPriceVat: app.totalsumVat,
            totalProduct: app.totalPrice,
            customer_id :app.cus_id,

            changeMo:app.changeMoneys,
            getMo: app.currentValue,
          })
          .then((res) => {
            app.checkvat = false;
            this.closeModal();
            $("#ModalPrint").modal("show");
            app.logCart = app.cartProducts;
            app.cartProducts = [];
            app.cus_id = ""
            app.custoemr = ""
            app.print_id = res.data.print_id;

            
           
            app.logGetMo = app.currentValue;
            app.currentValue = "";
            this.swalAlert("success", "ขายสินค้าสำเร็จ");
          });
      }
    },
    chageMoney() {
      if (this.cartProducts.length < 1) {
        this.swalAlert("warning", "กรุณาเลือกสินค้าก่อน");
      } else {
        $("#ModalSalepic").modal("show");
      }
    },
    addToCart: function (product) {
      const index = this.cartProducts.findIndex(
        (item) => item.product_code === product.product_code
      );
      if (index !== -1) {
        this.cartProducts[index].product_qty += 1;
      } else {
        if(app.typeprice == "product_price"){
          app.result_price = product.product_price;
        }else if(app.typeprice == "product_wholesale"){
          app.result_price = product.product_wholesale;
        }else if(app.typeprice == "product_retail"){
          app.result_price = product.product_retail;
        }
        const newItem = {
          product_id: product.product_id,
          product_code: product.product_code,
          product_name: product.product_name,
          product_price: app.result_price,
          product_cost: product.product_cost,
          product_discount: product.product_discount,
          product_qty: 1,
        };
        this.cartProducts.push(newItem);
      }
    },
    plusProduct(product) {
      this.cartProducts.find(function (item) {
        if (item.product_id === product.product_id) {
          item.product_qty += 1;
        }
      });
    },
    minusProduct(product) {
      var item = this.cartProducts.find(function (item) {
        return item.product_id === product.product_id;
      });

      if (item.product_qty > 1) {
        item.product_qty -= 1;
      } else {
        var index = this.cartProducts.indexOf(item);
        if (index !== -1) {
          this.cartProducts.splice(index, 1);
        }
      }
    },

    ClearCart() {
      this.cartProducts = [];
      this.error = "";
    },
    removeProduct(item) {
      var index = this.cartProducts.indexOf(item);
      this.cartProducts.splice(index, 1);
      this.error = 'ลบสินค้าเรียบร้อย'
      if(this.cartProducts.length <=0){
        this.checkvat = false
      }
  },
    findItem(e) {
      e.preventDefault();
      this.AllProdcut.find(function (item) {
        if (item.product_code == app.searchValue) {
          let index = app.cartProducts.findIndex(
            (product) => product.product_code == app.searchValue
          );

          if (index !== -1) {
            app.cartProducts[index].product_qty += 1;
          } else {
            if(app.typeprice == "product_price"){
              app.result_price = item.product_price;
            }else if(app.typeprice == "product_wholesale"){
              app.result_price = item.product_wholesale;
            }else if(app.typeprice == "product_retail"){
              app.result_price = item.product_retail;
            }
            app.cartProducts.push({
              product_id: item.product_id,
              product_code: item.product_code,
              product_name: item.product_name,
              product_price: app.result_price,
              product_cost: item.product_cost,
              product_discount: item.product_discount,
              product_qty: 1,
            });
          }

          app.check_find += 1;
        } else {
          app.check_find += 0;
        }
      });
      if (app.check_find == 0) {
        app.error = "ไม่พบสินค้า";
      } else {
        app.error = "เพิ่มสินค้าเรียบร้อย";
        app.check_find = 0;
      }
      app.searchValue = "";
      app.check_find = 0;
    },
    getAllProduct() {
      axios
        .post(this.path, {
          action: "getAllProduct",
        })
        .then((res) => {
          this.AllProdcut = res.data;
        });
    },
    getAllCategory() {
      axios
        .post(this.path, {
          action: "getAllCategory",
        })
        .then((res) => {
          this.AllCategory = res.data;
        });
    },
    swalAlert(type, title) {
      Swal.fire({
        position: "center",
        icon: type,
        text: title,
        showConfirmButton: false,
        timer: 900,
      });
    },
    addnum(num) {
      if (this.currentValue === "") {
        this.currentValue = String(num);
      } else {
        this.currentValue += String(num);
      }
    },
    deleteNumber() {
      this.currentValue = this.currentValue.slice(0, -1);
    },
    clearnum() {
      this.currentValue = "";
    },
    onChange(event) {
      if (event.target.value == "payment") {
        if(this.checkvat){
          this.currentValue = this.pricesumVat;
        }else{
          this.currentValue = this.totalPriceProduct;
        }
      } else {
        this.currentValue = "";
      }
    },
    onChangePrice(event){
      if(this.cartProducts.length >= 1 && this.typeprice !=  event.target.value){
        this.swalAlert("warning","เลือกราคาขายใด้ 1 ราคาเท่านั้น!")
      }else{
        this.typeprice = event.target.value;
      }
    },
    Checkvat(){
      if(this.cartProducts.length <= 0){
        this.swalAlert("warning", "กรุณาเลือกสินค้าก่อน");
      }else{
        this.checkvat = !this.checkvat
      }
    },

  },
  computed: {
    filteredProducts() {
      return this.AllProdcut.filter(product => {
        return product.product_name.toLowerCase().includes(this.searchQuery.toLowerCase())
      })
    },
    totalProduct_2(){
      return this.cartProducts.reduce(function (sum, item) {
        return sum + item.product_price * item.product_qty - item.product_discount * item.product_qty;
      }, 0);
    },
    changeMo() {
      if (this.currentValue == 0) {
        this.changeMoneys = parseFloat(0);
      } else {
        if(this.checkvat){
          this.changeMoneys =
            parseFloat(this.currentValue) - parseFloat(this.pricesumVat);
        }else{
          this.changeMoneys =
            parseFloat(this.currentValue) - parseFloat(this.totalPriceProduct);
        }
      }
      return this.changeMoneys;
    },
    totalPrice: function () {
      return this.cartProducts.reduce(function (sum, item) {
        return sum + item.product_price * item.product_qty;
      }, 0);
    },
    totalPriceProduct(){
      return this.cartProducts.reduce(function (sum, item) {
        return sum + item.product_price * item.product_qty - item.product_discount * item.product_qty;
      }, 0);
    },
    vatProduct(){
      return this.totalPriceProduct  * 7 / 100;
    },
    pricesumVat(){
      return this.totalPriceProduct + (this.totalPriceProduct  * 7 / 100);
    },
    countCart: function () {
      return parseFloat(this.cartProducts.length);
    },
    filteredProducts() {
      if (!this.searchCategory) {
        return this.AllProdcut;
      }
      const searchRegex = new RegExp(this.searchCategory, "i");

      this.search_img = this.AllProdcut.filter((product) =>
        searchRegex.test(product.product_category)
      )


      // console.log(this.search_img);
      // this.arrayimg = [];
      // for (let i = 0; i < this.search_img.length; i++) {
      //   this.arrayimg.push('assets/upload/' + this.search_img[i].product_img)
      // }

      return this.AllProdcut.filter((product) =>
        searchRegex.test(product.product_category)
      );
    },
    totalPages() {
      return Math.ceil(this.filteredProducts.length / this.pageSize);
    },
    currentPageProducts() {
      const startIndex = (this.currentPage - 1) * this.pageSize;
      const endIndex = startIndex + this.pageSize;
      return this.filteredProducts.slice(startIndex, endIndex);
    },
    startPage() {
      const totalPages = this.totalPages;
      const currentPage = this.currentPage;

      if (totalPages <= 8) {
        return 1;
      } else if (currentPage <= 4) {
        return 1;
      } else if (currentPage >= totalPages - 3) {
        return totalPages - 7;
      } else {
        return currentPage - 4;
      }
    },
    endPage() {
      const totalPages = this.totalPages;
      const currentPage = this.currentPage;

      if (totalPages <= 8) {
        return totalPages;
      } else if (currentPage <= 4) {
        return 8;
      } else if (currentPage >= totalPages - 3) {
        return totalPages;
      } else {
        return currentPage + 3;
      }
    },
    
  },
  mounted() {
    this.dataTable = $('#dataTable').DataTable({
      "language": {
          "sProcessing": "กำลังดำเนินการ...",
          "sLengthMenu": "แสดง_MENU_ แถว",
          "sZeroRecords": "ไม่พบข้อมูล",
          "sInfo": "แสดง _START_ ถึง _END_ จาก _TOTAL_ แถว",
          "sInfoEmpty": "แสดง 0 ถึง 0 จาก 0 แถว",
          "sInfoFiltered": "(กรองข้อมูล _MAX_ ทุกแถว)",
          "sInfoPostFix": "",
          "sSearch": "ค้นหา:",
          "sUrl": "",
          "oPaginate": {
              "sFirst": "เิริ่มต้น",
              "sPrevious": "ก่อนหน้า",
              "sNext": "ถัดไป",
              "sLast": "สุดท้าย"
          }
      }
  });
    this.getAllProduct();
    this.getAllCategory();
  },
});
