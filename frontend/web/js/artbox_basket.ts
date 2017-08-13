class ArtboxBasket {
    private _items;
    private _language;
    get items() {
        return this._items;
    }
    get language() {
        if(this._language === undefined) {
            var language_attr = $('html').attr('lang');
            console.log(language_attr);
            if(language_attr !== undefined) {
                var language = language_attr.substr(0, 2);
                if(language.length == 2) {
                    this._language = language;
                } else {
                    this._language = 'ru';
                }
            } else {
                this._language = 'ru';
            }
        }
        return this._language;
    }

    constructor() {
        this.init(true, true);
    }

    public init(update_modal, update_cart) {
        $.get('/' + this.language + '/basket', function (data) {
            this._items = data.basket;
            if (update_modal) {
                this.updateModal(data.modal, false);
            }
            if (update_cart) {
                this.updateCart(data.cart);
            }
        }.bind(this), 'json').fail(function () {
            console.error('Basket cannot be init');
        });
    }

    public add(product_variant_id, count) {
        $.post('/' + this.language + '/basket/add?product_variant_id=' + product_variant_id + '&count=' + count, function (data) {
            this._items = data.basket;
            this.updateModal(data.modal, data.cart, true);
        }.bind(this), 'json').fail(function (xhr, status, error) {
            console.error(error);
        });
    }

    public set(product_variant_id, count) {
        $.post('/' + this.language + '/basket/set?product_variant_id=' + product_variant_id + '&count=' + count, function (data) {
            this._items = data.basket;
            this.updateModal(data.modal, data.cart, true);
        }.bind(this), 'json').fail(function (xhr, status, error) {
            console.error(error);
        });
    }

    public remove(product_variant_id) {
        $.post('/' + this.language + '/basket/remove?product_variant_id=' + product_variant_id, function (data) {
            this._items = data.basket;
            this.updateModal(data.modal, data.cart, true);
        }.bind(this), 'json').fail(function (xhr, status, error) {
            console.error(error);
        });
    }

    public updateModal(modal, cart_html, show = false) {
        var modalBox = $('.basket_modal');
        modalBox.html(modal);
        this.updatePage(modal);
        if (cart_html) {
            this.updateCart(cart_html);
        }
        if(this.count < 1) {
            this.hideBasket();
        }
        if (show) {
            return show;
        }
    }

    public updateCart(cart_html) {
        var cart = $('.basket-wrapper, .basket-wrapper2');
        cart.html(cart_html);
    }

    public updatePage(modal) {
        var table = $(modal).find('.basket-tb');
        var sum = $(modal).find('.price-total-wr');
        var target = $('.basket_page').find('.basket-tb');
        var sum_target = $('.basket_page').find('.price-total-wr');
        if(this.count < 1 && target.length > 0) {
            location.reload();
            return;
        }
        if(table.length > 0 && sum.length > 0) {
            $(target).html($(table).html());
            $(sum_target).html($(sum).html());
        }
    }

    public hideBasket() {
        $('.basket_modal')
            .animate(
                {
                    opacity: 0,
                    top: '0'
                }, 200, function () {
                    $(this)
                        .css('display', 'none');
                    $('#overlay')
                        .fadeOut(400);
                }
            );
    }

    get count(): number {
        return Object.keys(this._items).length;
    }
}