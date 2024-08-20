import React, { Component } from 'react';
import { createRoot } from 'react-dom';
import axios from "axios";
import Swal from "sweetalert2";
import { sum } from "lodash";

class Cart extends Component {
    constructor(props) {
        super(props);
        this.state = {
            returncart: [],
            products: [],
            customers: [],
            invoice: [],
            barcode: "",
            search: "",
            customer_id: "",
            translations: {}, 
        };

        this.loadCart = this.loadCart.bind(this);
        this.loadInvoice = this.loadInvoice.bind(this);
        this.handleOnChangeBarcode = this.handleOnChangeBarcode.bind(this);
        this.handleScanBarcode = this.handleScanBarcode.bind(this);
        this.handleChangeQty = this.handleChangeQty.bind(this);
        this.handleEmptyCart = this.handleEmptyCart.bind(this);

        this.loadProducts = this.loadProducts.bind(this);
        this.handleChangeSearch = this.handleChangeSearch.bind(this);
        this.handleSeach = this.handleSeach.bind(this);
        this.setCustomerId = this.setCustomerId.bind(this);
        this.handleClickSubmit = this.handleClickSubmit.bind(this);
        this.loadTranslations = this.loadTranslations.bind(this);
    }

    componentDidMount() {
        // load user cart
        this.loadTranslations();
        this.loadCart();
        this.loadProducts();
        this.loadCustomers();
    }

    // load the transaltions for the react component
    loadTranslations() {
        axios.get("/admin/locale/cart").then((res) => {
            const translations = res.data;
            this.setState({ translations });
        }).catch((error) => {
            console.error("Error loading translations:", error);
        });
    }

    loadCustomers() {
        axios.get(`/admin/customers`).then((res) => {
            const customers = res.data;
            this.setState({ customers });
        });
    }

    loadProducts(search = "") {
        const query = !!search ? `?search=${search}` : "";
        axios.get(`/admin/products${query}`).then((res) => {
            const products = res.data.data;
            this.setState({ products });
        });
    }

    handleOnChangeBarcode(event) {
        const barcode = event.target.value;
        console.log(barcode);
        this.setState({ barcode });
    }

    loadCart() {
        axios.get("/admin/return").then((res) => {
            const returncart = res.data;
            this.setState({ returncart });
        });
    }

    loadInvoice() {
        axios.get("/admin/api/products").then((res) => {
            const returncart = res.data;
            this.setState({ invoice });
        });
    }

    handleScanBarcode(event) {
        event.preventDefault();
        const { barcode } = this.state;
        if (!!barcode) {
            axios
                .post("/admin/return", { barcode })
                .then((res) => {
                    this.loadCart();
                    this.setState({ barcode: "" });
                })
                .catch((err) => {
                    Swal.fire("Error!", err.response.data.message, "error");
                });
        }
    }
    handleChangeQty(product_id, qty) {
        const returncart = this.state.returncart.map((c) => {
            if (c.id === product_id) {
                c.pivot.quantity = qty;
            }
            return c;
        });

        this.setState({ returncart });
        if (!qty) return;

        axios
            .post("/admin/return/change-qty", { product_id, quantity: qty })
            .then((res) => {})
            .catch((err) => {
                Swal.fire("Error!", err.response.data.message, "error");
            });
    }

    getTotal(returncart) {
        const total = returncart.map((c) => c.pivot.quantity * c.price);
        return sum(total).toFixed(2);
    }
    handleClickDelete(product_id) {
        axios
            .post("/admin/return/delete", { product_id, _method: "DELETE" })
            .then((res) => {
                const returncart = this.state.returncart.filter((c) => c.id !== product_id);
                this.setState({ returncart });
            });
    }
    handleEmptyCart() {
        axios.post("/admin/return/empty", { _method: "DELETE" }).then((res) => {
            this.setState({ returncart: [] });
        });
    }
    handleChangeSearch(event) {
        const search = event.target.value;
        this.setState({ search });
    }
    handleSeach(event) {
        if (event.keyCode === 13) {
            this.loadProducts(event.target.value);
        }
    }

    addProductToCart(barcode) {
        let product = this.state.products.find((p) => p.barcode === barcode);
        if (!!product) {
            // if product is already in cart
            let cart = this.state.returncart.find((c) => c.id === product.id);
            if (!!cart) {
                // update quantity
                this.setState({
                    returncart: this.state.returncart.map((c) => {
                        if (
                            c.id === product.id &&
                            product.quantity > c.pivot.quantity
                        ) {
                            c.pivot.quantity = c.pivot.quantity + 1;
                        }
                        return c;
                    }),
                });
            } else {
                if (product.quantity > 0) {
                    product = {
                        ...product,
                        pivot: {
                            quantity: 1,
                            product_id: product.id,
                            user_id: 1,
                        },
                    };

                    this.setState({ returncart: [...this.state.returncart, product] });
                }
            }

            axios
                .post("/admin/return", { barcode })
                .then((res) => {
                    // this.loadCart();
                    console.log(res);
                })
                .catch((err) => {
                    Swal.fire("Error!", err.response.data.message, "error");
                });
        }
    }

     printInvoice = () => {
        var element = document.getElementById('aa');
      var originalContents = document.body.innerHTML;

      // Salin isi dari elemen yang ingin dicetak
      var printContents = element.innerHTML;

      // Ubah isi dari halaman hanya menjadi konten yang ingin dicetak
      document.body.innerHTML = printContents;

      // Pemanggilan print
      window.print();

    //   document.body.innerHTML = originalContents;

      location.reload();


      };
    

    setCustomerId(event) {
        this.setState({ customer_id: event.target.value });
    }
    handleClickSubmit(e) {
        e.preventDefault
        Swal.fire({
            title: this.state.translations["received_amount"],
            input: "text",
            inputValue: this.getTotal(this.state.returncart),
            cancelButtonText: this.state.translations['cancel_pay'],
            showCancelButton: true,
            confirmButtonText: this.state.translations["confirm_pay"],
            showLoaderOnConfirm: true,
            preConfirm: (amount) => {

                return axios
                    .post("/admin/addreturn", {
                        customer_id: this.state.customer_id,
                        amount,
                    })
                    .then((res) => { 
                        this.loadCart();
                        return res.data;
                    })
                    .catch((err) => {
                        Swal.showValidationMessage(err.response.data.message);
                    });
            },
            allowOutsideClick: () => !Swal.isLoading(),
        }).then((result) => {
            if (result.value =='success') {
                         
            }
        });
    }
    render() {
        const { returncart, products, customers, barcode, translations} = this.state;
        return (
            <div className="row">
                <div className="col-md-6 col-lg-4">
                    <div className="row mb-2">
                        <div className="col">
                            <form onSubmit={this.handleScanBarcode}>
                                <input
                                    type="text"
                                    className="form-control"
                                    placeholder={translations["scan_barcode"]}
                                    value={barcode}
                                    onChange={this.handleOnChangeBarcode}
                                />
                            </form>
                        </div>
                        <div className="col">
                            <select
                                className="form-control"
                                onChange={this.setCustomerId}
                            >
                                <option value="">{translations["general_customer"]}</option>
                                {customers.map((cus) => (
                                    <option
                                        key={cus.id}
                                        value={cus.id}
                                    >{`${cus.first_name} ${cus.last_name}`}</option>
                                ))}
                            </select>
                        </div>
                    </div>
                    <div id='aa'>
                    <div className="user-cart">
                        <div className="card">
                            <table className="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{translations["product_name"]}</th>
                                        <th>{translations["quantity"]}</th>
                                        <th className="text-right">{translations["price"]}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {returncart.map((c) => (
                                        <tr key={c.id}>
                                            <td>{c.name}</td>
                                            <td>
                                                <input
                                                    type="text"
                                                    className="form-control form-control-sm qty"
                                                    value={c.pivot.quantity}
                                                    onChange={(event) =>
                                                        this.handleChangeQty(
                                                            c.id,
                                                            event.target.value
                                                        )
                                                    }
                                                />
                                                <button
                                                    className="btn btn-danger btn-sm"
                                                    onClick={() =>
                                                        this.handleClickDelete(
                                                            c.id
                                                        )
                                                    }
                                                >
                                                    <i className="fas fa-trash"></i>
                                                </button>
                                            </td>
                                            <td className="text-right">
                                                {window.APP.currency_symbol}{" "}
                                                {(
                                                    c.price * c.pivot.quantity
                                                ).toFixed(2)}
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div className="row">
                        <div className="col">{translations["total"]}:</div>
                        <div className="col text-right">
                            {window.APP.currency_symbol} {this.getTotal(returncart)}
                        </div>
                    </div>

                    </div>
                    
                    
                    <div className="row">
                        <div className="col">
                            <button
                                type="button"
                                className="btn btn-danger btn-block"
                                onClick={this.handleEmptyCart}
                                disabled={!returncart.length}
                            >
                                {translations["cancel"]}
                            </button>
                        </div>
                        <div className="col">
                            <button
                                type="button"
                                className="btn btn-primary btn-block"
                                disabled={!returncart.length}
                                onClick={this.handleClickSubmit}
                            >
                                {translations["checkout"]}
                            </button>
                        </div>
                    </div>
                </div>
                <div className="col-md-6 col-lg-8">
                    <div className="mb-2">
                        <input
                            type="text"
                            className="form-control"
                            placeholder={translations["search_product"] + "..."}
                            onChange={this.handleChangeSearch}
                            onKeyDown={this.handleSeach}
                        />
                    </div>
                    <div className="order-product">
    {products.map((p) =>
        Array.from({ length: p.quantity }).map((_, index) => (
            <div
                onClick={() => this.addProductToCart(p.barcode)}
                key={`${p.id}-${index}`}
                className="item"
            >
                <img src={p.image_url} alt="" />
                <h5
                    style={
                        window.APP.warning_quantity > p.quantity
                            ? { color: "red" }
                            : {}
                    }
                >
                    {p.name} <p>Exp:{p.exp ? p.exp : '-'}</p>
                </h5>
                
            </div>
        ))
    )}
</div>
                </div>
            </div>
        );
    }
}

export default Cart;

const root = document.getElementById('return');
if (root) {
    const rootInstance = createRoot(root);
    rootInstance.render(<Cart />);
}
