--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.4
-- Dumped by pg_dump version 9.5.4

-- Started on 2016-10-31 16:40:15 EET

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 1 (class 3079 OID 12397)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 2779 (class 0 OID 0)
-- Dependencies: 1
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_with_oids = false;

--
-- TOC entry 190 (class 1259 OID 19920)
-- Name: article; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE article (
    id integer NOT NULL,
    image character varying(255),
    created_at integer
);


--
-- TOC entry 192 (class 1259 OID 19943)
-- Name: article_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE article_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2780 (class 0 OID 0)
-- Dependencies: 192
-- Name: article_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE article_id_seq OWNED BY article.id;


--
-- TOC entry 191 (class 1259 OID 19925)
-- Name: article_lang; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE article_lang (
    article_id integer NOT NULL,
    language_id integer NOT NULL,
    title character varying(255) NOT NULL,
    body text NOT NULL,
    meta_title character varying(255),
    meta_keywords character varying(255),
    meta_description character varying(255),
    seo_text text,
    h1 character varying(255),
    body_preview text,
    alias character varying(255)
);


--
-- TOC entry 189 (class 1259 OID 19897)
-- Name: auth_assignment; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE auth_assignment (
    item_name character varying(64) NOT NULL,
    user_id character varying(64) NOT NULL,
    created_at integer
);


--
-- TOC entry 187 (class 1259 OID 19868)
-- Name: auth_item; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE auth_item (
    name character varying(64) NOT NULL,
    type integer NOT NULL,
    description text,
    rule_name character varying(64),
    data text,
    created_at integer,
    updated_at integer
);


--
-- TOC entry 188 (class 1259 OID 19882)
-- Name: auth_item_child; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE auth_item_child (
    parent character varying(64) NOT NULL,
    child character varying(64) NOT NULL
);


--
-- TOC entry 186 (class 1259 OID 19860)
-- Name: auth_rule; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE auth_rule (
    name character varying(64) NOT NULL,
    data text,
    created_at integer,
    updated_at integer
);


--
-- TOC entry 193 (class 1259 OID 19946)
-- Name: banner; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE banner (
    id integer NOT NULL,
    url character varying(255),
    status smallint
);


--
-- TOC entry 194 (class 1259 OID 19949)
-- Name: banner_banner_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE banner_banner_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2781 (class 0 OID 0)
-- Dependencies: 194
-- Name: banner_banner_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE banner_banner_id_seq OWNED BY banner.id;


--
-- TOC entry 195 (class 1259 OID 19951)
-- Name: banner_lang; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE banner_lang (
    banner_id integer NOT NULL,
    language_id integer NOT NULL,
    alt character varying(255),
    title character varying(255),
    image character varying(255)
);


--
-- TOC entry 196 (class 1259 OID 19957)
-- Name: bg; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE bg (
    id integer NOT NULL,
    url character varying(250) NOT NULL,
    image character varying(250) NOT NULL
);


--
-- TOC entry 197 (class 1259 OID 19963)
-- Name: bg_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE bg_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2782 (class 0 OID 0)
-- Dependencies: 197
-- Name: bg_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE bg_id_seq OWNED BY bg.id;


--
-- TOC entry 198 (class 1259 OID 19965)
-- Name: bg_lang; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE bg_lang (
    bg_id integer NOT NULL,
    language_id integer NOT NULL,
    title character varying(255) NOT NULL
);


--
-- TOC entry 199 (class 1259 OID 19968)
-- Name: brand; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE brand (
    id integer NOT NULL,
    image character varying(255),
    in_menu boolean,
    remote_id character varying(255)
);


--
-- TOC entry 200 (class 1259 OID 19974)
-- Name: brand_brand_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE brand_brand_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2783 (class 0 OID 0)
-- Dependencies: 200
-- Name: brand_brand_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE brand_brand_id_seq OWNED BY brand.id;


--
-- TOC entry 201 (class 1259 OID 19976)
-- Name: brand_lang; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE brand_lang (
    brand_id integer NOT NULL,
    language_id integer NOT NULL,
    title character varying(255) NOT NULL,
    meta_title character varying(255),
    meta_robots character varying(255),
    meta_description character varying(255),
    seo_text text,
    alias character varying(255)
);


--
-- TOC entry 202 (class 1259 OID 19982)
-- Name: category; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE category (
    id integer NOT NULL,
    parent_id integer DEFAULT 0 NOT NULL,
    path integer[],
    depth integer DEFAULT 0 NOT NULL,
    image character varying(255),
    product_unit_id integer,
    remote_id character varying(255)
);


--
-- TOC entry 203 (class 1259 OID 19990)
-- Name: category_category_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE category_category_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2784 (class 0 OID 0)
-- Dependencies: 203
-- Name: category_category_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE category_category_id_seq OWNED BY category.id;


--
-- TOC entry 204 (class 1259 OID 19992)
-- Name: category_lang; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE category_lang (
    category_id integer NOT NULL,
    language_id integer NOT NULL,
    title character varying(255) NOT NULL,
    meta_title character varying(255),
    meta_robots character varying(255),
    meta_description character varying(255),
    seo_text text,
    h1 character varying(255),
    alias character varying(255)
);


--
-- TOC entry 205 (class 1259 OID 19998)
-- Name: customer; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE customer (
    id integer NOT NULL,
    username character varying(255) NOT NULL,
    password_hash character varying(255) NOT NULL,
    name character varying(255),
    surname character varying(255),
    phone character varying(255),
    gender character varying(32),
    birth_day integer,
    birth_month integer,
    birth_year integer,
    body text,
    group_id integer,
    email character varying(255),
    auth_key character varying(32),
    password_reset_token character varying(255),
    status smallint,
    created_at integer,
    updated_at integer,
    role character varying(255)
);


--
-- TOC entry 206 (class 1259 OID 20004)
-- Name: customer_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE customer_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2785 (class 0 OID 0)
-- Dependencies: 206
-- Name: customer_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE customer_id_seq OWNED BY customer.id;


--
-- TOC entry 207 (class 1259 OID 20006)
-- Name: event; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE event (
    id integer NOT NULL,
    image character varying(255),
    created_at integer,
    updated_at integer,
    end_at integer
);


--
-- TOC entry 208 (class 1259 OID 20009)
-- Name: event_event_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE event_event_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2786 (class 0 OID 0)
-- Dependencies: 208
-- Name: event_event_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE event_event_id_seq OWNED BY event.id;


--
-- TOC entry 209 (class 1259 OID 20011)
-- Name: event_lang; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE event_lang (
    event_id integer NOT NULL,
    language_id integer NOT NULL,
    title character varying(255) NOT NULL,
    body text NOT NULL,
    meta_title character varying(255),
    meta_description character varying(255),
    seo_text text,
    h1 character varying(255),
    alias character varying(255)
);


--
-- TOC entry 210 (class 1259 OID 20017)
-- Name: feedback; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE feedback (
    id integer NOT NULL,
    name character varying(255),
    phone character varying(255) NOT NULL,
    created_at integer,
    ip character varying(255)
);


--
-- TOC entry 211 (class 1259 OID 20023)
-- Name: feedback_feedback_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE feedback_feedback_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2787 (class 0 OID 0)
-- Dependencies: 211
-- Name: feedback_feedback_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE feedback_feedback_id_seq OWNED BY feedback.id;


--
-- TOC entry 185 (class 1259 OID 19842)
-- Name: language; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE language (
    id integer NOT NULL,
    url character varying(255) NOT NULL,
    local character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    "default" boolean DEFAULT false NOT NULL,
    created_at integer NOT NULL,
    updated_at integer NOT NULL,
    status boolean DEFAULT false NOT NULL
);


--
-- TOC entry 184 (class 1259 OID 19840)
-- Name: language_language_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE language_language_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2788 (class 0 OID 0)
-- Dependencies: 184
-- Name: language_language_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE language_language_id_seq OWNED BY language.id;


--
-- TOC entry 181 (class 1259 OID 19817)
-- Name: migration; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE migration (
    version character varying(180) NOT NULL,
    apply_time integer
);


--
-- TOC entry 212 (class 1259 OID 20025)
-- Name: order; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "order" (
    id integer NOT NULL,
    user_id integer,
    name character varying(255) NOT NULL,
    phone character varying(255),
    phone2 character varying(255),
    email character varying(255),
    adress character varying(255),
    body text,
    total double precision,
    date_time timestamp(0) without time zone,
    date_dedline date,
    reserve character varying(255),
    status character varying(255),
    comment text,
    label integer,
    pay integer,
    numbercard integer,
    delivery integer,
    declaration character varying(255),
    stock character varying(255),
    consignment character varying(255),
    payment character varying(255),
    insurance character varying(255),
    amount_imposed double precision,
    shipping_by character varying(255),
    city character varying(255)
);


--
-- TOC entry 213 (class 1259 OID 20031)
-- Name: order_delivery; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE order_delivery (
    id integer NOT NULL,
    parent_id integer,
    value integer,
    sort integer
);


--
-- TOC entry 215 (class 1259 OID 20036)
-- Name: order_delivery_lang; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE order_delivery_lang (
    order_delivery_id integer NOT NULL,
    language_id integer NOT NULL,
    title character varying(255) NOT NULL,
    text text NOT NULL
);


--
-- TOC entry 217 (class 1259 OID 20044)
-- Name: order_label; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE order_label (
    id integer NOT NULL,
    label character varying(255)
);


--
-- TOC entry 219 (class 1259 OID 20049)
-- Name: order_label_lang; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE order_label_lang (
    order_label_id integer NOT NULL,
    language_id integer NOT NULL,
    title character varying(255) NOT NULL
);


--
-- TOC entry 220 (class 1259 OID 20052)
-- Name: order_product; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE order_product (
    id integer NOT NULL,
    order_id integer NOT NULL,
    product_variant_id integer,
    product_name character varying(255),
    name character varying(255),
    sku character varying(255),
    price double precision,
    count integer,
    sum_cost double precision
);


--
-- TOC entry 214 (class 1259 OID 20034)
-- Name: orders_delivery_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE orders_delivery_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2789 (class 0 OID 0)
-- Dependencies: 214
-- Name: orders_delivery_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE orders_delivery_id_seq OWNED BY order_delivery.id;


--
-- TOC entry 216 (class 1259 OID 20042)
-- Name: orders_id_seq1; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE orders_id_seq1
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2790 (class 0 OID 0)
-- Dependencies: 216
-- Name: orders_id_seq1; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE orders_id_seq1 OWNED BY "order".id;


--
-- TOC entry 218 (class 1259 OID 20047)
-- Name: orders_label_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE orders_label_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2791 (class 0 OID 0)
-- Dependencies: 218
-- Name: orders_label_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE orders_label_id_seq OWNED BY order_label.id;


--
-- TOC entry 221 (class 1259 OID 20058)
-- Name: orders_products_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE orders_products_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2792 (class 0 OID 0)
-- Dependencies: 221
-- Name: orders_products_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE orders_products_id_seq OWNED BY order_product.id;


--
-- TOC entry 222 (class 1259 OID 20060)
-- Name: page; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE page (
    id integer NOT NULL,
    in_menu boolean DEFAULT false NOT NULL
);


--
-- TOC entry 223 (class 1259 OID 20064)
-- Name: page_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE page_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2793 (class 0 OID 0)
-- Dependencies: 223
-- Name: page_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE page_id_seq OWNED BY page.id;


--
-- TOC entry 224 (class 1259 OID 20066)
-- Name: page_lang; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE page_lang (
    page_id integer NOT NULL,
    language_id integer NOT NULL,
    title character varying(255) NOT NULL,
    body text NOT NULL,
    meta_title character varying(255),
    meta_keywords character varying(255),
    meta_description character varying(255),
    seo_text text,
    h1 character varying(255),
    alias character varying(255)
);


--
-- TOC entry 225 (class 1259 OID 20072)
-- Name: product; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE product (
    id integer NOT NULL,
    brand_id integer,
    video text,
    is_top boolean DEFAULT false,
    is_discount boolean DEFAULT false,
    is_new boolean DEFAULT false,
    remote_id character varying(255)
);


--
-- TOC entry 226 (class 1259 OID 20081)
-- Name: product_category; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE product_category (
    product_id integer NOT NULL,
    category_id integer NOT NULL
);


--
-- TOC entry 227 (class 1259 OID 20084)
-- Name: product_image; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE product_image (
    product_variant_id integer,
    image character varying(255),
    alt character varying(255),
    title character varying(255),
    product_id integer NOT NULL,
    product_image_id integer NOT NULL
);


--
-- TOC entry 228 (class 1259 OID 20090)
-- Name: product_image_product_image_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE product_image_product_image_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2794 (class 0 OID 0)
-- Dependencies: 228
-- Name: product_image_product_image_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE product_image_product_image_id_seq OWNED BY product_image.product_image_id;


--
-- TOC entry 229 (class 1259 OID 20092)
-- Name: product_lang; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE product_lang (
    product_id integer NOT NULL,
    language_id integer NOT NULL,
    title character varying(255) NOT NULL,
    description text,
    alias character varying(255)
);


--
-- TOC entry 230 (class 1259 OID 20098)
-- Name: product_option; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE product_option (
    product_id integer NOT NULL,
    option_id integer NOT NULL
);


--
-- TOC entry 231 (class 1259 OID 20101)
-- Name: product_product_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE product_product_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2795 (class 0 OID 0)
-- Dependencies: 231
-- Name: product_product_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE product_product_id_seq OWNED BY product.id;


--
-- TOC entry 232 (class 1259 OID 20103)
-- Name: product_stock; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE product_stock (
    stock_id integer NOT NULL,
    quantity integer NOT NULL,
    product_variant_id integer NOT NULL
);


--
-- TOC entry 233 (class 1259 OID 20106)
-- Name: stock; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE stock (
    id integer NOT NULL,
    remote_id character varying(255)
);


--
-- TOC entry 234 (class 1259 OID 20109)
-- Name: product_stock_product_stock_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE product_stock_product_stock_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2796 (class 0 OID 0)
-- Dependencies: 234
-- Name: product_stock_product_stock_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE product_stock_product_stock_id_seq OWNED BY stock.id;


--
-- TOC entry 235 (class 1259 OID 20111)
-- Name: product_unit; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE product_unit (
    id integer NOT NULL,
    is_default boolean
);


--
-- TOC entry 236 (class 1259 OID 20114)
-- Name: product_unit_lang; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE product_unit_lang (
    product_unit_id integer NOT NULL,
    language_id integer NOT NULL,
    title character varying(255) NOT NULL,
    short character varying(255)
);


--
-- TOC entry 237 (class 1259 OID 20120)
-- Name: product_unit_product_unit_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE product_unit_product_unit_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2797 (class 0 OID 0)
-- Dependencies: 237
-- Name: product_unit_product_unit_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE product_unit_product_unit_id_seq OWNED BY product_unit.id;


--
-- TOC entry 238 (class 1259 OID 20122)
-- Name: product_variant; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE product_variant (
    id integer NOT NULL,
    product_id integer NOT NULL,
    sku character varying(255) NOT NULL,
    price double precision,
    price_old double precision,
    stock double precision,
    product_unit_id integer,
    remote_id character varying(255)
);


--
-- TOC entry 239 (class 1259 OID 20128)
-- Name: product_variant_lang; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE product_variant_lang (
    product_variant_id integer NOT NULL,
    language_id integer NOT NULL,
    title character varying(255) NOT NULL
);


--
-- TOC entry 240 (class 1259 OID 20131)
-- Name: product_variant_option; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE product_variant_option (
    product_variant_id integer NOT NULL,
    option_id integer NOT NULL
);


--
-- TOC entry 241 (class 1259 OID 20134)
-- Name: product_variant_product_variant_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE product_variant_product_variant_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2798 (class 0 OID 0)
-- Dependencies: 241
-- Name: product_variant_product_variant_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE product_variant_product_variant_id_seq OWNED BY product_variant.id;


--
-- TOC entry 242 (class 1259 OID 20136)
-- Name: seo; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE seo (
    id integer NOT NULL,
    url character varying(255) NOT NULL
);


--
-- TOC entry 243 (class 1259 OID 20139)
-- Name: seo_category; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE seo_category (
    id integer NOT NULL,
    controller character varying(100),
    status smallint
);


--
-- TOC entry 244 (class 1259 OID 20142)
-- Name: seo_category_lang; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE seo_category_lang (
    seo_category_id integer NOT NULL,
    language_id integer NOT NULL,
    title character varying(255)
);


--
-- TOC entry 245 (class 1259 OID 20145)
-- Name: seo_category_seo_category_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE seo_category_seo_category_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2799 (class 0 OID 0)
-- Dependencies: 245
-- Name: seo_category_seo_category_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE seo_category_seo_category_id_seq OWNED BY seo_category.id;


--
-- TOC entry 246 (class 1259 OID 20147)
-- Name: seo_dynamic; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE seo_dynamic (
    id integer NOT NULL,
    seo_category_id integer,
    action character varying(200),
    fields character varying(255),
    status smallint,
    param character varying(255)
);


--
-- TOC entry 247 (class 1259 OID 20153)
-- Name: seo_dynamic_lang; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE seo_dynamic_lang (
    seo_dynamic_id integer NOT NULL,
    language_id integer NOT NULL,
    title character varying(255),
    meta_title text,
    h1 character varying(255),
    key character varying(255),
    meta character varying(255),
    meta_description text,
    seo_text text
);


--
-- TOC entry 248 (class 1259 OID 20159)
-- Name: seo_dynamic_seo_dynamic_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE seo_dynamic_seo_dynamic_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2800 (class 0 OID 0)
-- Dependencies: 248
-- Name: seo_dynamic_seo_dynamic_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE seo_dynamic_seo_dynamic_id_seq OWNED BY seo_dynamic.id;


--
-- TOC entry 249 (class 1259 OID 20161)
-- Name: seo_lang; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE seo_lang (
    seo_id integer NOT NULL,
    language_id integer NOT NULL,
    title character varying(255),
    meta_description text,
    h1 character varying(255),
    meta character varying(255),
    seo_text text
);


--
-- TOC entry 250 (class 1259 OID 20167)
-- Name: seo_seo_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE seo_seo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2801 (class 0 OID 0)
-- Dependencies: 250
-- Name: seo_seo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE seo_seo_id_seq OWNED BY seo.id;


--
-- TOC entry 251 (class 1259 OID 20169)
-- Name: service; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE service (
    id integer NOT NULL,
    image character varying(255),
    created_at integer,
    updated_at integer
);


--
-- TOC entry 252 (class 1259 OID 20172)
-- Name: service_lang; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE service_lang (
    service_id integer NOT NULL,
    language_id integer NOT NULL,
    title character varying(255) NOT NULL,
    body text NOT NULL,
    seo_text text,
    meta_title character varying(255),
    meta_description character varying(255),
    h1 character varying(255),
    alias character varying(255)
);


--
-- TOC entry 253 (class 1259 OID 20178)
-- Name: service_service_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE service_service_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2802 (class 0 OID 0)
-- Dependencies: 253
-- Name: service_service_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE service_service_id_seq OWNED BY service.id;


--
-- TOC entry 254 (class 1259 OID 20180)
-- Name: slider; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE slider (
    id integer NOT NULL,
    speed integer,
    duration integer,
    title character varying(200),
    status smallint,
    width integer,
    height integer
);


--
-- TOC entry 255 (class 1259 OID 20183)
-- Name: slider_image; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE slider_image (
    id integer NOT NULL,
    slider_id integer NOT NULL,
    image character varying(255),
    url character varying(255),
    status smallint,
    sort integer
);


--
-- TOC entry 256 (class 1259 OID 20189)
-- Name: slider_image_lang; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE slider_image_lang (
    slider_image_id integer NOT NULL,
    language_id integer NOT NULL,
    title character varying(255),
    alt character varying(255)
);


--
-- TOC entry 257 (class 1259 OID 20195)
-- Name: slider_image_slider_image_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE slider_image_slider_image_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2803 (class 0 OID 0)
-- Dependencies: 257
-- Name: slider_image_slider_image_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE slider_image_slider_image_id_seq OWNED BY slider_image.id;


--
-- TOC entry 258 (class 1259 OID 20197)
-- Name: slider_slider_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE slider_slider_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2804 (class 0 OID 0)
-- Dependencies: 258
-- Name: slider_slider_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE slider_slider_id_seq OWNED BY slider.id;


--
-- TOC entry 259 (class 1259 OID 20199)
-- Name: stock_lang; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE stock_lang (
    stock_id integer NOT NULL,
    language_id integer NOT NULL,
    title character varying(255) NOT NULL
);


--
-- TOC entry 260 (class 1259 OID 20202)
-- Name: tax_group; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE tax_group (
    id integer NOT NULL,
    is_filter boolean DEFAULT false,
    level integer,
    sort integer DEFAULT 0,
    display boolean DEFAULT true,
    is_menu boolean,
    remote_id character varying(255)
);


--
-- TOC entry 261 (class 1259 OID 20208)
-- Name: tax_group_lang; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE tax_group_lang (
    tax_group_id integer NOT NULL,
    language_id integer NOT NULL,
    title character varying(255) NOT NULL,
    description text,
    alias character varying(255)
);


--
-- TOC entry 262 (class 1259 OID 20214)
-- Name: tax_group_tax_group_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE tax_group_tax_group_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2805 (class 0 OID 0)
-- Dependencies: 262
-- Name: tax_group_tax_group_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE tax_group_tax_group_id_seq OWNED BY tax_group.id;


--
-- TOC entry 263 (class 1259 OID 20216)
-- Name: tax_group_to_category; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE tax_group_to_category (
    tax_group_to_category_id integer NOT NULL,
    tax_group_id integer NOT NULL,
    category_id integer NOT NULL
);


--
-- TOC entry 264 (class 1259 OID 20219)
-- Name: tax_group_to_category_tax_group_to_category_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE tax_group_to_category_tax_group_to_category_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2806 (class 0 OID 0)
-- Dependencies: 264
-- Name: tax_group_to_category_tax_group_to_category_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE tax_group_to_category_tax_group_to_category_id_seq OWNED BY tax_group_to_category.tax_group_to_category_id;


--
-- TOC entry 265 (class 1259 OID 20221)
-- Name: tax_option; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE tax_option (
    id bigint NOT NULL,
    tax_group_id integer NOT NULL,
    sort integer DEFAULT 0,
    image character varying(255),
    remote_id character varying(255)
);


--
-- TOC entry 266 (class 1259 OID 20229)
-- Name: tax_option_lang; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE tax_option_lang (
    tax_option_id integer NOT NULL,
    language_id integer NOT NULL,
    value character varying(255) NOT NULL,
    alias character varying(255)
);


--
-- TOC entry 267 (class 1259 OID 20235)
-- Name: tax_option_tax_option_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE tax_option_tax_option_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2807 (class 0 OID 0)
-- Dependencies: 267
-- Name: tax_option_tax_option_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE tax_option_tax_option_id_seq OWNED BY tax_option.id;


--
-- TOC entry 183 (class 1259 OID 19824)
-- Name: user; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "user" (
    id integer NOT NULL,
    username character varying(255) NOT NULL,
    auth_key character varying(32) NOT NULL,
    password_hash character varying(255) NOT NULL,
    password_reset_token character varying(255),
    email character varying(255) NOT NULL,
    status smallint DEFAULT 10 NOT NULL,
    created_at integer NOT NULL,
    updated_at integer NOT NULL
);


--
-- TOC entry 182 (class 1259 OID 19822)
-- Name: user_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2808 (class 0 OID 0)
-- Dependencies: 182
-- Name: user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE user_id_seq OWNED BY "user".id;


--
-- TOC entry 2342 (class 2604 OID 19945)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY article ALTER COLUMN id SET DEFAULT nextval('article_id_seq'::regclass);


--
-- TOC entry 2343 (class 2604 OID 20237)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY banner ALTER COLUMN id SET DEFAULT nextval('banner_banner_id_seq'::regclass);


--
-- TOC entry 2344 (class 2604 OID 20238)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY bg ALTER COLUMN id SET DEFAULT nextval('bg_id_seq'::regclass);


--
-- TOC entry 2345 (class 2604 OID 20239)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY brand ALTER COLUMN id SET DEFAULT nextval('brand_brand_id_seq'::regclass);


--
-- TOC entry 2348 (class 2604 OID 20240)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY category ALTER COLUMN id SET DEFAULT nextval('category_category_id_seq'::regclass);


--
-- TOC entry 2349 (class 2604 OID 20241)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY customer ALTER COLUMN id SET DEFAULT nextval('customer_id_seq'::regclass);


--
-- TOC entry 2350 (class 2604 OID 20242)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY event ALTER COLUMN id SET DEFAULT nextval('event_event_id_seq'::regclass);


--
-- TOC entry 2351 (class 2604 OID 20243)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY feedback ALTER COLUMN id SET DEFAULT nextval('feedback_feedback_id_seq'::regclass);


--
-- TOC entry 2339 (class 2604 OID 19845)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY language ALTER COLUMN id SET DEFAULT nextval('language_language_id_seq'::regclass);


--
-- TOC entry 2352 (class 2604 OID 20244)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY "order" ALTER COLUMN id SET DEFAULT nextval('orders_id_seq1'::regclass);


--
-- TOC entry 2353 (class 2604 OID 20245)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY order_delivery ALTER COLUMN id SET DEFAULT nextval('orders_delivery_id_seq'::regclass);


--
-- TOC entry 2354 (class 2604 OID 20246)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY order_label ALTER COLUMN id SET DEFAULT nextval('orders_label_id_seq'::regclass);


--
-- TOC entry 2355 (class 2604 OID 20247)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY order_product ALTER COLUMN id SET DEFAULT nextval('orders_products_id_seq'::regclass);


--
-- TOC entry 2357 (class 2604 OID 20248)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY page ALTER COLUMN id SET DEFAULT nextval('page_id_seq'::regclass);


--
-- TOC entry 2361 (class 2604 OID 20249)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY product ALTER COLUMN id SET DEFAULT nextval('product_product_id_seq'::regclass);


--
-- TOC entry 2362 (class 2604 OID 20250)
-- Name: product_image_id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY product_image ALTER COLUMN product_image_id SET DEFAULT nextval('product_image_product_image_id_seq'::regclass);


--
-- TOC entry 2364 (class 2604 OID 20251)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY product_unit ALTER COLUMN id SET DEFAULT nextval('product_unit_product_unit_id_seq'::regclass);


--
-- TOC entry 2365 (class 2604 OID 20252)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY product_variant ALTER COLUMN id SET DEFAULT nextval('product_variant_product_variant_id_seq'::regclass);


--
-- TOC entry 2366 (class 2604 OID 20253)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY seo ALTER COLUMN id SET DEFAULT nextval('seo_seo_id_seq'::regclass);


--
-- TOC entry 2367 (class 2604 OID 20254)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY seo_category ALTER COLUMN id SET DEFAULT nextval('seo_category_seo_category_id_seq'::regclass);


--
-- TOC entry 2368 (class 2604 OID 20255)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY seo_dynamic ALTER COLUMN id SET DEFAULT nextval('seo_dynamic_seo_dynamic_id_seq'::regclass);


--
-- TOC entry 2369 (class 2604 OID 20256)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY service ALTER COLUMN id SET DEFAULT nextval('service_service_id_seq'::regclass);


--
-- TOC entry 2370 (class 2604 OID 20257)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY slider ALTER COLUMN id SET DEFAULT nextval('slider_slider_id_seq'::regclass);


--
-- TOC entry 2371 (class 2604 OID 20258)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY slider_image ALTER COLUMN id SET DEFAULT nextval('slider_image_slider_image_id_seq'::regclass);


--
-- TOC entry 2363 (class 2604 OID 20259)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY stock ALTER COLUMN id SET DEFAULT nextval('product_stock_product_stock_id_seq'::regclass);


--
-- TOC entry 2375 (class 2604 OID 20260)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY tax_group ALTER COLUMN id SET DEFAULT nextval('tax_group_tax_group_id_seq'::regclass);


--
-- TOC entry 2376 (class 2604 OID 20261)
-- Name: tax_group_to_category_id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY tax_group_to_category ALTER COLUMN tax_group_to_category_id SET DEFAULT nextval('tax_group_to_category_tax_group_to_category_id_seq'::regclass);


--
-- TOC entry 2378 (class 2604 OID 20262)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY tax_option ALTER COLUMN id SET DEFAULT nextval('tax_option_tax_option_id_seq'::regclass);


--
-- TOC entry 2337 (class 2604 OID 19827)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY "user" ALTER COLUMN id SET DEFAULT nextval('user_id_seq'::regclass);


--
-- TOC entry 2695 (class 0 OID 19920)
-- Dependencies: 190
-- Data for Name: article; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO article (id, image, created_at) VALUES (1, 'pic1_2.jpg', 1477180800);


--
-- TOC entry 2809 (class 0 OID 0)
-- Dependencies: 192
-- Name: article_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('article_id_seq', 1, true);


--
-- TOC entry 2696 (class 0 OID 19925)
-- Dependencies: 191
-- Data for Name: article_lang; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO article_lang (article_id, language_id, title, body, meta_title, meta_keywords, meta_description, seo_text, h1, body_preview, alias) VALUES (1, 2, 'Тестовое название', '<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p>

<p>Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo.</p>

<p>Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus.</p>

<p>Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus.</p>

<p>Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet nibh. Donec sodales sagittis magna. Sed consequat, leo eget bibendum sodales, augue velit cursus nunc,</p>
', '', '', '', '', '', '', 'testovoe-nazvanie');
INSERT INTO article_lang (article_id, language_id, title, body, meta_title, meta_keywords, meta_description, seo_text, h1, body_preview, alias) VALUES (1, 3, 'Тестова назва', '<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p>

<p>Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo.</p>

<p>Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus.</p>

<p>Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus.</p>

<p>Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet nibh. Donec sodales sagittis magna. Sed consequat, leo eget bibendum sodales, augue velit cursus nunc,</p>
', '', '', '', '', '', '', 'testova-nazva');


--
-- TOC entry 2694 (class 0 OID 19897)
-- Dependencies: 189
-- Data for Name: auth_assignment; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO auth_assignment (item_name, user_id, created_at) VALUES ('admin', '1', 1);


--
-- TOC entry 2692 (class 0 OID 19868)
-- Dependencies: 187
-- Data for Name: auth_item; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('orders', 2, 'Заказы', NULL, NULL, 1463735507, 1463736083);
INSERT INTO auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('seo', 2, 'SEO', NULL, NULL, 1463736416, 1463736416);
INSERT INTO auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('seo-category', 2, 'SEO Категории', NULL, NULL, 1463736448, 1463736448);
INSERT INTO auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('product', 2, 'Продукты', NULL, NULL, 1463736531, 1463736531);
INSERT INTO auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('rubrication', 2, 'Рубрикации', NULL, NULL, 1463736556, 1463736556);
INSERT INTO auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('relation', 2, 'Зависимости', NULL, NULL, 1463736575, 1463736575);
INSERT INTO auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('banner', 2, 'Банеры', NULL, NULL, 1463738868, 1463738868);
INSERT INTO auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('page', 2, 'текстовые страницы', NULL, NULL, 1463743391, 1463743391);
INSERT INTO auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('user', 2, 'Пользователи', NULL, NULL, 1463743496, 1463743496);
INSERT INTO auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('event', 2, 'Акции', NULL, NULL, 1463743669, 1463743669);
INSERT INTO auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('brand', 2, 'Brand', NULL, NULL, 1463744537, 1463744537);
INSERT INTO auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('category', 2, 'Category', NULL, NULL, 1463745810, 1463745814);
INSERT INTO auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('slider', 2, 'Слайдер', NULL, NULL, 1463749607, 1463749607);
INSERT INTO auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('bg', 2, 'Фон', NULL, NULL, 1463749640, 1463749640);
INSERT INTO auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('customer', 2, 'Пользователи', NULL, NULL, 1463749675, 1463749675);
INSERT INTO auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('permit', 2, 'Права', NULL, NULL, 1463750149, 1463750149);
INSERT INTO auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('slider-image', 2, 'Картинки слайдера', NULL, NULL, 1464185251, 1464185251);
INSERT INTO auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('person', 1, 'Персона', NULL, NULL, NULL, 1464186293);
INSERT INTO auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('debug', 2, 'debug', NULL, NULL, 1464194890, 1464194903);
INSERT INTO auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('seo-dynamic', 2, 'Динамическое SEO', NULL, NULL, 1464273621, 1464273621);
INSERT INTO auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('manager', 1, 'Менеджер', NULL, NULL, 1464670630, 1464670630);
INSERT INTO auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('artbox-comments', 2, 'Комменты', NULL, NULL, 1470303707, 1470303707);
INSERT INTO auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('feedback', 2, 'Обратная связь', NULL, NULL, 1473168138, 1473168138);
INSERT INTO auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('file', 2, 'Файлы', NULL, NULL, 1473427802, 1473427802);
INSERT INTO auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('service', 2, 'Услуги', NULL, NULL, 1473509543, 1473509543);
INSERT INTO auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('gii', 2, 'GII', NULL, NULL, 1473856734, 1473856734);
INSERT INTO auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('project', 2, 'Project', NULL, NULL, 1473857270, 1473857270);
INSERT INTO auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('certificate', 2, 'Сертификаты', NULL, NULL, 1474377855, 1474377855);
INSERT INTO auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('delivery', 2, 'Доставка', NULL, NULL, 1476115225, 1476115265);
INSERT INTO auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('label', 2, 'Статус заказа', NULL, NULL, 1476888240, 1476888240);
INSERT INTO auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('admin', 1, 'Админ сайта', NULL, NULL, 1463735454, 1477085013);
INSERT INTO auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('article', 2, 'Статьи', NULL, NULL, 1463748344, 1477256755);


--
-- TOC entry 2693 (class 0 OID 19882)
-- Dependencies: 188
-- Data for Name: auth_item_child; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO auth_item_child (parent, child) VALUES ('person', 'customer');
INSERT INTO auth_item_child (parent, child) VALUES ('person', 'category');
INSERT INTO auth_item_child (parent, child) VALUES ('person', 'orders');
INSERT INTO auth_item_child (parent, child) VALUES ('manager', 'orders');
INSERT INTO auth_item_child (parent, child) VALUES ('admin', 'orders');
INSERT INTO auth_item_child (parent, child) VALUES ('admin', 'seo');
INSERT INTO auth_item_child (parent, child) VALUES ('admin', 'seo-category');
INSERT INTO auth_item_child (parent, child) VALUES ('admin', 'product');
INSERT INTO auth_item_child (parent, child) VALUES ('admin', 'rubrication');
INSERT INTO auth_item_child (parent, child) VALUES ('admin', 'relation');
INSERT INTO auth_item_child (parent, child) VALUES ('admin', 'banner');
INSERT INTO auth_item_child (parent, child) VALUES ('admin', 'page');
INSERT INTO auth_item_child (parent, child) VALUES ('admin', 'user');
INSERT INTO auth_item_child (parent, child) VALUES ('admin', 'event');
INSERT INTO auth_item_child (parent, child) VALUES ('admin', 'brand');
INSERT INTO auth_item_child (parent, child) VALUES ('admin', 'category');
INSERT INTO auth_item_child (parent, child) VALUES ('admin', 'slider');
INSERT INTO auth_item_child (parent, child) VALUES ('admin', 'bg');
INSERT INTO auth_item_child (parent, child) VALUES ('admin', 'customer');
INSERT INTO auth_item_child (parent, child) VALUES ('admin', 'permit');
INSERT INTO auth_item_child (parent, child) VALUES ('admin', 'slider-image');
INSERT INTO auth_item_child (parent, child) VALUES ('admin', 'debug');
INSERT INTO auth_item_child (parent, child) VALUES ('admin', 'seo-dynamic');
INSERT INTO auth_item_child (parent, child) VALUES ('admin', 'artbox-comments');
INSERT INTO auth_item_child (parent, child) VALUES ('admin', 'feedback');
INSERT INTO auth_item_child (parent, child) VALUES ('admin', 'file');
INSERT INTO auth_item_child (parent, child) VALUES ('admin', 'service');
INSERT INTO auth_item_child (parent, child) VALUES ('admin', 'gii');
INSERT INTO auth_item_child (parent, child) VALUES ('admin', 'project');
INSERT INTO auth_item_child (parent, child) VALUES ('admin', 'certificate');
INSERT INTO auth_item_child (parent, child) VALUES ('admin', 'delivery');
INSERT INTO auth_item_child (parent, child) VALUES ('admin', 'label');
INSERT INTO auth_item_child (parent, child) VALUES ('person', 'article');
INSERT INTO auth_item_child (parent, child) VALUES ('admin', 'article');


--
-- TOC entry 2691 (class 0 OID 19860)
-- Dependencies: 186
-- Data for Name: auth_rule; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2698 (class 0 OID 19946)
-- Dependencies: 193
-- Data for Name: banner; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2810 (class 0 OID 0)
-- Dependencies: 194
-- Name: banner_banner_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('banner_banner_id_seq', 1, true);


--
-- TOC entry 2700 (class 0 OID 19951)
-- Dependencies: 195
-- Data for Name: banner_lang; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2701 (class 0 OID 19957)
-- Dependencies: 196
-- Data for Name: bg; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2811 (class 0 OID 0)
-- Dependencies: 197
-- Name: bg_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('bg_id_seq', 1, false);


--
-- TOC entry 2703 (class 0 OID 19965)
-- Dependencies: 198
-- Data for Name: bg_lang; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2704 (class 0 OID 19968)
-- Dependencies: 199
-- Data for Name: brand; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2812 (class 0 OID 0)
-- Dependencies: 200
-- Name: brand_brand_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('brand_brand_id_seq', 1, false);


--
-- TOC entry 2706 (class 0 OID 19976)
-- Dependencies: 201
-- Data for Name: brand_lang; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2707 (class 0 OID 19982)
-- Dependencies: 202
-- Data for Name: category; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2813 (class 0 OID 0)
-- Dependencies: 203
-- Name: category_category_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('category_category_id_seq', 1, false);


--
-- TOC entry 2709 (class 0 OID 19992)
-- Dependencies: 204
-- Data for Name: category_lang; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2710 (class 0 OID 19998)
-- Dependencies: 205
-- Data for Name: customer; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2814 (class 0 OID 0)
-- Dependencies: 206
-- Name: customer_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('customer_id_seq', 1, false);


--
-- TOC entry 2712 (class 0 OID 20006)
-- Dependencies: 207
-- Data for Name: event; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2815 (class 0 OID 0)
-- Dependencies: 208
-- Name: event_event_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('event_event_id_seq', 1, false);


--
-- TOC entry 2714 (class 0 OID 20011)
-- Dependencies: 209
-- Data for Name: event_lang; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2715 (class 0 OID 20017)
-- Dependencies: 210
-- Data for Name: feedback; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2816 (class 0 OID 0)
-- Dependencies: 211
-- Name: feedback_feedback_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('feedback_feedback_id_seq', 1, false);


--
-- TOC entry 2690 (class 0 OID 19842)
-- Dependencies: 185
-- Data for Name: language; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO language (id, url, local, name, "default", created_at, updated_at, status) VALUES (1, 'en', 'en-EN', 'English', false, 1477164376, 1477164376, false);
INSERT INTO language (id, url, local, name, "default", created_at, updated_at, status) VALUES (2, 'ru', 'ru-RU', 'Русский', true, 1477164376, 1477164376, true);
INSERT INTO language (id, url, local, name, "default", created_at, updated_at, status) VALUES (3, 'ua', 'ua-UA', 'Українська', false, 1477164376, 1477164376, true);


--
-- TOC entry 2817 (class 0 OID 0)
-- Dependencies: 184
-- Name: language_language_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('language_language_id_seq', 1, false);


--
-- TOC entry 2686 (class 0 OID 19817)
-- Dependencies: 181
-- Data for Name: migration; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO migration (version, apply_time) VALUES ('m000000_000000_base', 1477163247);
INSERT INTO migration (version, apply_time) VALUES ('m130524_201442_init', 1477163249);
INSERT INTO migration (version, apply_time) VALUES ('m160829_104745_create_table_language', 1477164376);
INSERT INTO migration (version, apply_time) VALUES ('m160829_105345_add_default_languages', 1477164376);
INSERT INTO migration (version, apply_time) VALUES ('m160901_140639_add_ukrainian_language', 1477164376);
INSERT INTO migration (version, apply_time) VALUES ('m160927_124151_add_status_column', 1477164376);
INSERT INTO migration (version, apply_time) VALUES ('m140506_102106_rbac_init', 1477172015);


--
-- TOC entry 2717 (class 0 OID 20025)
-- Dependencies: 212
-- Data for Name: order; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2718 (class 0 OID 20031)
-- Dependencies: 213
-- Data for Name: order_delivery; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2720 (class 0 OID 20036)
-- Dependencies: 215
-- Data for Name: order_delivery_lang; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2722 (class 0 OID 20044)
-- Dependencies: 217
-- Data for Name: order_label; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2724 (class 0 OID 20049)
-- Dependencies: 219
-- Data for Name: order_label_lang; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2725 (class 0 OID 20052)
-- Dependencies: 220
-- Data for Name: order_product; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2818 (class 0 OID 0)
-- Dependencies: 214
-- Name: orders_delivery_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('orders_delivery_id_seq', 1, false);


--
-- TOC entry 2819 (class 0 OID 0)
-- Dependencies: 216
-- Name: orders_id_seq1; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('orders_id_seq1', 1, false);


--
-- TOC entry 2820 (class 0 OID 0)
-- Dependencies: 218
-- Name: orders_label_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('orders_label_id_seq', 1, false);


--
-- TOC entry 2821 (class 0 OID 0)
-- Dependencies: 221
-- Name: orders_products_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('orders_products_id_seq', 1, false);


--
-- TOC entry 2727 (class 0 OID 20060)
-- Dependencies: 222
-- Data for Name: page; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2822 (class 0 OID 0)
-- Dependencies: 223
-- Name: page_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('page_id_seq', 1, false);


--
-- TOC entry 2729 (class 0 OID 20066)
-- Dependencies: 224
-- Data for Name: page_lang; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2730 (class 0 OID 20072)
-- Dependencies: 225
-- Data for Name: product; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2731 (class 0 OID 20081)
-- Dependencies: 226
-- Data for Name: product_category; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2732 (class 0 OID 20084)
-- Dependencies: 227
-- Data for Name: product_image; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2823 (class 0 OID 0)
-- Dependencies: 228
-- Name: product_image_product_image_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('product_image_product_image_id_seq', 1, false);


--
-- TOC entry 2734 (class 0 OID 20092)
-- Dependencies: 229
-- Data for Name: product_lang; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2735 (class 0 OID 20098)
-- Dependencies: 230
-- Data for Name: product_option; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2824 (class 0 OID 0)
-- Dependencies: 231
-- Name: product_product_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('product_product_id_seq', 1, true);


--
-- TOC entry 2737 (class 0 OID 20103)
-- Dependencies: 232
-- Data for Name: product_stock; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2825 (class 0 OID 0)
-- Dependencies: 234
-- Name: product_stock_product_stock_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('product_stock_product_stock_id_seq', 1, true);


--
-- TOC entry 2740 (class 0 OID 20111)
-- Dependencies: 235
-- Data for Name: product_unit; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2741 (class 0 OID 20114)
-- Dependencies: 236
-- Data for Name: product_unit_lang; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2826 (class 0 OID 0)
-- Dependencies: 237
-- Name: product_unit_product_unit_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('product_unit_product_unit_id_seq', 1, false);


--
-- TOC entry 2743 (class 0 OID 20122)
-- Dependencies: 238
-- Data for Name: product_variant; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2744 (class 0 OID 20128)
-- Dependencies: 239
-- Data for Name: product_variant_lang; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2745 (class 0 OID 20131)
-- Dependencies: 240
-- Data for Name: product_variant_option; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2827 (class 0 OID 0)
-- Dependencies: 241
-- Name: product_variant_product_variant_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('product_variant_product_variant_id_seq', 2, true);


--
-- TOC entry 2747 (class 0 OID 20136)
-- Dependencies: 242
-- Data for Name: seo; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2748 (class 0 OID 20139)
-- Dependencies: 243
-- Data for Name: seo_category; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2749 (class 0 OID 20142)
-- Dependencies: 244
-- Data for Name: seo_category_lang; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2828 (class 0 OID 0)
-- Dependencies: 245
-- Name: seo_category_seo_category_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('seo_category_seo_category_id_seq', 1, false);


--
-- TOC entry 2751 (class 0 OID 20147)
-- Dependencies: 246
-- Data for Name: seo_dynamic; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2752 (class 0 OID 20153)
-- Dependencies: 247
-- Data for Name: seo_dynamic_lang; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2829 (class 0 OID 0)
-- Dependencies: 248
-- Name: seo_dynamic_seo_dynamic_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('seo_dynamic_seo_dynamic_id_seq', 1, false);


--
-- TOC entry 2754 (class 0 OID 20161)
-- Dependencies: 249
-- Data for Name: seo_lang; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2830 (class 0 OID 0)
-- Dependencies: 250
-- Name: seo_seo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('seo_seo_id_seq', 1, false);


--
-- TOC entry 2756 (class 0 OID 20169)
-- Dependencies: 251
-- Data for Name: service; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2757 (class 0 OID 20172)
-- Dependencies: 252
-- Data for Name: service_lang; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2831 (class 0 OID 0)
-- Dependencies: 253
-- Name: service_service_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('service_service_id_seq', 1, false);


--
-- TOC entry 2759 (class 0 OID 20180)
-- Dependencies: 254
-- Data for Name: slider; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2760 (class 0 OID 20183)
-- Dependencies: 255
-- Data for Name: slider_image; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2761 (class 0 OID 20189)
-- Dependencies: 256
-- Data for Name: slider_image_lang; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2832 (class 0 OID 0)
-- Dependencies: 257
-- Name: slider_image_slider_image_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('slider_image_slider_image_id_seq', 1, false);


--
-- TOC entry 2833 (class 0 OID 0)
-- Dependencies: 258
-- Name: slider_slider_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('slider_slider_id_seq', 1, false);


--
-- TOC entry 2738 (class 0 OID 20106)
-- Dependencies: 233
-- Data for Name: stock; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2764 (class 0 OID 20199)
-- Dependencies: 259
-- Data for Name: stock_lang; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2765 (class 0 OID 20202)
-- Dependencies: 260
-- Data for Name: tax_group; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2766 (class 0 OID 20208)
-- Dependencies: 261
-- Data for Name: tax_group_lang; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2834 (class 0 OID 0)
-- Dependencies: 262
-- Name: tax_group_tax_group_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('tax_group_tax_group_id_seq', 1, false);


--
-- TOC entry 2768 (class 0 OID 20216)
-- Dependencies: 263
-- Data for Name: tax_group_to_category; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2835 (class 0 OID 0)
-- Dependencies: 264
-- Name: tax_group_to_category_tax_group_to_category_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('tax_group_to_category_tax_group_to_category_id_seq', 1, false);


--
-- TOC entry 2770 (class 0 OID 20221)
-- Dependencies: 265
-- Data for Name: tax_option; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2771 (class 0 OID 20229)
-- Dependencies: 266
-- Data for Name: tax_option_lang; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2836 (class 0 OID 0)
-- Dependencies: 267
-- Name: tax_option_tax_option_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('tax_option_tax_option_id_seq', 1, false);


--
-- TOC entry 2688 (class 0 OID 19824)
-- Dependencies: 183
-- Data for Name: user; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO "user" (id, username, auth_key, password_hash, password_reset_token, email, status, created_at, updated_at) VALUES (1, 'admin', 'i2_-MRfZEwla24-OTwpPrELXC6XAqgrO', '$2y$13$SUNNdADQ0d1ZZTUZlXELOu.b1ma5YlcckxgFpiT3VIg4HDCoZ0dQq', NULL, 'admin@gmail.com', 10, 1477163323, 1477163323);


--
-- TOC entry 2837 (class 0 OID 0)
-- Dependencies: 182
-- Name: user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('user_id_seq', 1, true);


--
-- TOC entry 2401 (class 2606 OID 19924)
-- Name: article_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY article
    ADD CONSTRAINT article_pkey PRIMARY KEY (id);


--
-- TOC entry 2399 (class 2606 OID 19901)
-- Name: auth_assignment_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY auth_assignment
    ADD CONSTRAINT auth_assignment_pkey PRIMARY KEY (item_name, user_id);


--
-- TOC entry 2397 (class 2606 OID 19886)
-- Name: auth_item_child_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY auth_item_child
    ADD CONSTRAINT auth_item_child_pkey PRIMARY KEY (parent, child);


--
-- TOC entry 2394 (class 2606 OID 19875)
-- Name: auth_item_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY auth_item
    ADD CONSTRAINT auth_item_pkey PRIMARY KEY (name);


--
-- TOC entry 2392 (class 2606 OID 19867)
-- Name: auth_rule_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY auth_rule
    ADD CONSTRAINT auth_rule_pkey PRIMARY KEY (name);


--
-- TOC entry 2405 (class 2606 OID 20264)
-- Name: banner_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY banner
    ADD CONSTRAINT banner_pkey PRIMARY KEY (id);


--
-- TOC entry 2408 (class 2606 OID 20267)
-- Name: bg_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY bg
    ADD CONSTRAINT bg_pkey PRIMARY KEY (id);


--
-- TOC entry 2411 (class 2606 OID 20269)
-- Name: brand_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY brand
    ADD CONSTRAINT brand_pkey PRIMARY KEY (id);


--
-- TOC entry 2416 (class 2606 OID 20271)
-- Name: category_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY category
    ADD CONSTRAINT category_pkey PRIMARY KEY (id);


--
-- TOC entry 2421 (class 2606 OID 20273)
-- Name: customer_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY customer
    ADD CONSTRAINT customer_pkey PRIMARY KEY (id);


--
-- TOC entry 2423 (class 2606 OID 20275)
-- Name: event_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY event
    ADD CONSTRAINT event_pkey PRIMARY KEY (id);


--
-- TOC entry 2427 (class 2606 OID 20277)
-- Name: feedback_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY feedback
    ADD CONSTRAINT feedback_pkey PRIMARY KEY (id);


--
-- TOC entry 2390 (class 2606 OID 19851)
-- Name: language_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY language
    ADD CONSTRAINT language_pkey PRIMARY KEY (id);


--
-- TOC entry 2380 (class 2606 OID 19821)
-- Name: migration_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY migration
    ADD CONSTRAINT migration_pkey PRIMARY KEY (version);


--
-- TOC entry 2431 (class 2606 OID 20279)
-- Name: orders_delivery_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY order_delivery
    ADD CONSTRAINT orders_delivery_pkey PRIMARY KEY (id);


--
-- TOC entry 2434 (class 2606 OID 20281)
-- Name: orders_label_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY order_label
    ADD CONSTRAINT orders_label_pkey PRIMARY KEY (id);


--
-- TOC entry 2429 (class 2606 OID 20283)
-- Name: orders_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "order"
    ADD CONSTRAINT orders_pkey PRIMARY KEY (id);


--
-- TOC entry 2438 (class 2606 OID 20285)
-- Name: orders_products_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY order_product
    ADD CONSTRAINT orders_products_pkey PRIMARY KEY (id);


--
-- TOC entry 2440 (class 2606 OID 20287)
-- Name: page_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY page
    ADD CONSTRAINT page_pkey PRIMARY KEY (id);


--
-- TOC entry 2450 (class 2606 OID 20289)
-- Name: product_image_id_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY product_image
    ADD CONSTRAINT product_image_id_pk PRIMARY KEY (product_image_id);


--
-- TOC entry 2454 (class 2606 OID 20291)
-- Name: product_option_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY product_option
    ADD CONSTRAINT product_option_pkey PRIMARY KEY (product_id, option_id);


--
-- TOC entry 2445 (class 2606 OID 20293)
-- Name: product_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY product
    ADD CONSTRAINT product_pkey PRIMARY KEY (id);


--
-- TOC entry 2460 (class 2606 OID 20295)
-- Name: product_unit_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY product_unit
    ADD CONSTRAINT product_unit_pkey PRIMARY KEY (id);


--
-- TOC entry 2469 (class 2606 OID 20297)
-- Name: product_variant_option_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY product_variant_option
    ADD CONSTRAINT product_variant_option_pkey PRIMARY KEY (product_variant_id, option_id);


--
-- TOC entry 2463 (class 2606 OID 20299)
-- Name: product_variant_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY product_variant
    ADD CONSTRAINT product_variant_pkey PRIMARY KEY (id);


--
-- TOC entry 2473 (class 2606 OID 20301)
-- Name: seo_category_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY seo_category
    ADD CONSTRAINT seo_category_pkey PRIMARY KEY (id);


--
-- TOC entry 2476 (class 2606 OID 20303)
-- Name: seo_dynamic_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY seo_dynamic
    ADD CONSTRAINT seo_dynamic_pkey PRIMARY KEY (id);


--
-- TOC entry 2471 (class 2606 OID 20305)
-- Name: seo_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY seo
    ADD CONSTRAINT seo_pkey PRIMARY KEY (id);


--
-- TOC entry 2480 (class 2606 OID 20307)
-- Name: service_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY service
    ADD CONSTRAINT service_pkey PRIMARY KEY (id);


--
-- TOC entry 2486 (class 2606 OID 20309)
-- Name: slider_image_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY slider_image
    ADD CONSTRAINT slider_image_pkey PRIMARY KEY (id);


--
-- TOC entry 2484 (class 2606 OID 20311)
-- Name: slider_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY slider
    ADD CONSTRAINT slider_pkey PRIMARY KEY (id);


--
-- TOC entry 2458 (class 2606 OID 20313)
-- Name: stock_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY stock
    ADD CONSTRAINT stock_pkey PRIMARY KEY (id);


--
-- TOC entry 2491 (class 2606 OID 20315)
-- Name: tax_group_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY tax_group
    ADD CONSTRAINT tax_group_pkey PRIMARY KEY (id);


--
-- TOC entry 2497 (class 2606 OID 20317)
-- Name: tax_group_to_category_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY tax_group_to_category
    ADD CONSTRAINT tax_group_to_category_pkey PRIMARY KEY (tax_group_to_category_id);


--
-- TOC entry 2499 (class 2606 OID 20319)
-- Name: tax_option_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY tax_option
    ADD CONSTRAINT tax_option_pkey PRIMARY KEY (id);


--
-- TOC entry 2382 (class 2606 OID 19839)
-- Name: user_email_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT user_email_key UNIQUE (email);


--
-- TOC entry 2384 (class 2606 OID 19837)
-- Name: user_password_reset_token_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT user_password_reset_token_key UNIQUE (password_reset_token);


--
-- TOC entry 2386 (class 2606 OID 19833)
-- Name: user_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT user_pkey PRIMARY KEY (id);


--
-- TOC entry 2388 (class 2606 OID 19835)
-- Name: user_username_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT user_username_key UNIQUE (username);


--
-- TOC entry 2402 (class 1259 OID 19942)
-- Name: articles_lang_alias; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX articles_lang_alias ON article_lang USING btree (alias);


--
-- TOC entry 2403 (class 1259 OID 19941)
-- Name: articles_lang_article_language_key; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX articles_lang_article_language_key ON article_lang USING btree (article_id, language_id);


--
-- TOC entry 2406 (class 1259 OID 20320)
-- Name: banner_lang_banner_language_key; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX banner_lang_banner_language_key ON banner_lang USING btree (banner_id, language_id);


--
-- TOC entry 2409 (class 1259 OID 20321)
-- Name: bg_lang_bg_language_key; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX bg_lang_bg_language_key ON bg_lang USING btree (bg_id, language_id);


--
-- TOC entry 2413 (class 1259 OID 20322)
-- Name: brand_lang_alias; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX brand_lang_alias ON brand_lang USING btree (alias);


--
-- TOC entry 2414 (class 1259 OID 20323)
-- Name: brand_lang_brand_language_key; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX brand_lang_brand_language_key ON brand_lang USING btree (brand_id, language_id);


--
-- TOC entry 2412 (class 1259 OID 20324)
-- Name: brand_remote_id_uindex; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX brand_remote_id_uindex ON brand USING btree (remote_id);


--
-- TOC entry 2418 (class 1259 OID 20325)
-- Name: category_lang_alias; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX category_lang_alias ON category_lang USING btree (alias);


--
-- TOC entry 2419 (class 1259 OID 20326)
-- Name: category_lang_category_language_key; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX category_lang_category_language_key ON category_lang USING btree (category_id, language_id);


--
-- TOC entry 2417 (class 1259 OID 20327)
-- Name: category_remote_id_uindex; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX category_remote_id_uindex ON category USING btree (remote_id);


--
-- TOC entry 2424 (class 1259 OID 20328)
-- Name: event_lang_alias; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX event_lang_alias ON event_lang USING btree (alias);


--
-- TOC entry 2425 (class 1259 OID 20329)
-- Name: event_lang_event_language_key; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX event_lang_event_language_key ON event_lang USING btree (event_id, language_id);


--
-- TOC entry 2436 (class 1259 OID 20330)
-- Name: fki_orders_products_product_variant_product_variant_id_fk; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX fki_orders_products_product_variant_product_variant_id_fk ON order_product USING btree (product_variant_id);


--
-- TOC entry 2443 (class 1259 OID 20331)
-- Name: fki_product_brand_fk; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX fki_product_brand_fk ON product USING btree (brand_id);


--
-- TOC entry 2447 (class 1259 OID 20332)
-- Name: fki_product_id_fk; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX fki_product_id_fk ON product_image USING btree (product_id);


--
-- TOC entry 2455 (class 1259 OID 20334)
-- Name: fki_product_stock_product_variant_id_fkey; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX fki_product_stock_product_variant_id_fkey ON product_stock USING btree (product_variant_id);


--
-- TOC entry 2456 (class 1259 OID 20335)
-- Name: fki_product_stock_stock_id_fkey; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX fki_product_stock_stock_id_fkey ON product_stock USING btree (stock_id);


--
-- TOC entry 2448 (class 1259 OID 20336)
-- Name: fki_product_variant_id_fkey; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX fki_product_variant_id_fkey ON product_image USING btree (product_variant_id);


--
-- TOC entry 2467 (class 1259 OID 20337)
-- Name: fki_product_variant_option_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX fki_product_variant_option_id ON product_variant_option USING btree (option_id);


--
-- TOC entry 2395 (class 1259 OID 19881)
-- Name: idx-auth_item-type; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idx-auth_item-type" ON auth_item USING btree (type);


--
-- TOC entry 2432 (class 1259 OID 20338)
-- Name: orders_delivery_lang_orders_delivery_language_key; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX orders_delivery_lang_orders_delivery_language_key ON order_delivery_lang USING btree (order_delivery_id, language_id);


--
-- TOC entry 2435 (class 1259 OID 20339)
-- Name: orders_label_lang_orders_label_language_key; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX orders_label_lang_orders_label_language_key ON order_label_lang USING btree (order_label_id, language_id);


--
-- TOC entry 2441 (class 1259 OID 20340)
-- Name: page_lang_alias; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX page_lang_alias ON page_lang USING btree (alias);


--
-- TOC entry 2442 (class 1259 OID 20341)
-- Name: page_lang_page_language_key; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX page_lang_page_language_key ON page_lang USING btree (page_id, language_id);


--
-- TOC entry 2451 (class 1259 OID 20342)
-- Name: product_lang_alias; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX product_lang_alias ON product_lang USING btree (alias);


--
-- TOC entry 2452 (class 1259 OID 20343)
-- Name: product_lang_product_language_key; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX product_lang_product_language_key ON product_lang USING btree (product_id, language_id);


--
-- TOC entry 2446 (class 1259 OID 20344)
-- Name: product_remote_id_uindex; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX product_remote_id_uindex ON product USING btree (remote_id);


--
-- TOC entry 2461 (class 1259 OID 20345)
-- Name: product_unit_lang_product_unit_language_key; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX product_unit_lang_product_unit_language_key ON product_unit_lang USING btree (product_unit_id, language_id);


--
-- TOC entry 2466 (class 1259 OID 20346)
-- Name: product_variant_lang_product_variant_language_key; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX product_variant_lang_product_variant_language_key ON product_variant_lang USING btree (product_variant_id, language_id);


--
-- TOC entry 2464 (class 1259 OID 20347)
-- Name: product_variant_product_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX product_variant_product_id_index ON product_variant USING btree (product_id);


--
-- TOC entry 2465 (class 1259 OID 20348)
-- Name: product_variant_remote_id_uindex; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX product_variant_remote_id_uindex ON product_variant USING btree (remote_id);


--
-- TOC entry 2474 (class 1259 OID 20349)
-- Name: seo_category_lang_seo_category_language_key; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX seo_category_lang_seo_category_language_key ON seo_category_lang USING btree (seo_category_id, language_id);


--
-- TOC entry 2477 (class 1259 OID 20350)
-- Name: seo_dynamic_lang_seo_dynamic_language_key; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX seo_dynamic_lang_seo_dynamic_language_key ON seo_dynamic_lang USING btree (seo_dynamic_id, language_id);


--
-- TOC entry 2478 (class 1259 OID 20351)
-- Name: seo_lang_seo_language_key; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX seo_lang_seo_language_key ON seo_lang USING btree (seo_id, language_id);


--
-- TOC entry 2481 (class 1259 OID 20352)
-- Name: service_lang_alias; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX service_lang_alias ON service_lang USING btree (alias);


--
-- TOC entry 2482 (class 1259 OID 20353)
-- Name: service_lang_service_language_key; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX service_lang_service_language_key ON service_lang USING btree (service_id, language_id);


--
-- TOC entry 2487 (class 1259 OID 20354)
-- Name: slider_image_lang_slider_image_language_key; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX slider_image_lang_slider_image_language_key ON slider_image_lang USING btree (slider_image_id, language_id);


--
-- TOC entry 2488 (class 1259 OID 20355)
-- Name: stock_lang_stock_language_key; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX stock_lang_stock_language_key ON stock_lang USING btree (stock_id, language_id);


--
-- TOC entry 2489 (class 1259 OID 20356)
-- Name: tax_group_is_filter_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX tax_group_is_filter_index ON tax_group USING btree (is_filter);


--
-- TOC entry 2494 (class 1259 OID 20357)
-- Name: tax_group_lang_alias; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX tax_group_lang_alias ON tax_group_lang USING btree (alias);


--
-- TOC entry 2495 (class 1259 OID 20358)
-- Name: tax_group_lang_tax_group_language_key; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX tax_group_lang_tax_group_language_key ON tax_group_lang USING btree (tax_group_id, language_id);


--
-- TOC entry 2492 (class 1259 OID 20359)
-- Name: tax_group_remote_id_uindex; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX tax_group_remote_id_uindex ON tax_group USING btree (remote_id);


--
-- TOC entry 2493 (class 1259 OID 20360)
-- Name: tax_group_sort_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX tax_group_sort_index ON tax_group USING btree (sort);


--
-- TOC entry 2503 (class 1259 OID 20361)
-- Name: tax_option_lang_alias; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX tax_option_lang_alias ON tax_option_lang USING btree (alias);


--
-- TOC entry 2504 (class 1259 OID 20362)
-- Name: tax_option_lang_tax_option_language_key; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX tax_option_lang_tax_option_language_key ON tax_option_lang USING btree (tax_option_id, language_id);


--
-- TOC entry 2500 (class 1259 OID 20363)
-- Name: tax_option_remote_id_uindex; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX tax_option_remote_id_uindex ON tax_option USING btree (remote_id);


--
-- TOC entry 2501 (class 1259 OID 20364)
-- Name: tax_option_sort_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX tax_option_sort_index ON tax_option USING btree (sort);


--
-- TOC entry 2502 (class 1259 OID 20365)
-- Name: tax_option_tax_group_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX tax_option_tax_group_id_index ON tax_option USING btree (tax_group_id);


--
-- TOC entry 2509 (class 2606 OID 19931)
-- Name: article_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY article_lang
    ADD CONSTRAINT article_fk FOREIGN KEY (article_id) REFERENCES article(id);


--
-- TOC entry 2508 (class 2606 OID 19902)
-- Name: auth_assignment_item_name_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY auth_assignment
    ADD CONSTRAINT auth_assignment_item_name_fkey FOREIGN KEY (item_name) REFERENCES auth_item(name) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2507 (class 2606 OID 19892)
-- Name: auth_item_child_child_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY auth_item_child
    ADD CONSTRAINT auth_item_child_child_fkey FOREIGN KEY (child) REFERENCES auth_item(name) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2506 (class 2606 OID 19887)
-- Name: auth_item_child_parent_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY auth_item_child
    ADD CONSTRAINT auth_item_child_parent_fkey FOREIGN KEY (parent) REFERENCES auth_item(name) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2505 (class 2606 OID 19876)
-- Name: auth_item_rule_name_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY auth_item
    ADD CONSTRAINT auth_item_rule_name_fkey FOREIGN KEY (rule_name) REFERENCES auth_rule(name) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2511 (class 2606 OID 20366)
-- Name: banner_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY banner_lang
    ADD CONSTRAINT banner_fk FOREIGN KEY (banner_id) REFERENCES banner(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2513 (class 2606 OID 20371)
-- Name: bg_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY bg_lang
    ADD CONSTRAINT bg_fk FOREIGN KEY (bg_id) REFERENCES bg(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2515 (class 2606 OID 20376)
-- Name: brand_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY brand_lang
    ADD CONSTRAINT brand_fk FOREIGN KEY (brand_id) REFERENCES brand(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2567 (class 2606 OID 20381)
-- Name: category_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY tax_group_to_category
    ADD CONSTRAINT category_fk FOREIGN KEY (category_id) REFERENCES category(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2518 (class 2606 OID 20386)
-- Name: category_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY category_lang
    ADD CONSTRAINT category_fk FOREIGN KEY (category_id) REFERENCES category(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2517 (class 2606 OID 20391)
-- Name: category_product_unit_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY category
    ADD CONSTRAINT category_product_unit_fkey FOREIGN KEY (product_unit_id) REFERENCES product_unit(id);


--
-- TOC entry 2520 (class 2606 OID 20396)
-- Name: event_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY event_lang
    ADD CONSTRAINT event_fk FOREIGN KEY (event_id) REFERENCES event(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2533 (class 2606 OID 20401)
-- Name: fki_category_id; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY product_category
    ADD CONSTRAINT fki_category_id FOREIGN KEY (category_id) REFERENCES category(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2534 (class 2606 OID 20406)
-- Name: fki_product_id; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY product_category
    ADD CONSTRAINT fki_product_id FOREIGN KEY (product_id) REFERENCES product(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2569 (class 2606 OID 20411)
-- Name: fki_tax_option_tax_group_id; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY tax_option
    ADD CONSTRAINT fki_tax_option_tax_group_id FOREIGN KEY (tax_group_id) REFERENCES tax_group(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2510 (class 2606 OID 19936)
-- Name: language_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY article_lang
    ADD CONSTRAINT language_fk FOREIGN KEY (language_id) REFERENCES language(id);


--
-- TOC entry 2530 (class 2606 OID 20416)
-- Name: language_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY page_lang
    ADD CONSTRAINT language_fk FOREIGN KEY (language_id) REFERENCES language(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2512 (class 2606 OID 20421)
-- Name: language_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY banner_lang
    ADD CONSTRAINT language_fk FOREIGN KEY (language_id) REFERENCES language(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2514 (class 2606 OID 20426)
-- Name: language_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY bg_lang
    ADD CONSTRAINT language_fk FOREIGN KEY (language_id) REFERENCES language(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2516 (class 2606 OID 20431)
-- Name: language_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY brand_lang
    ADD CONSTRAINT language_fk FOREIGN KEY (language_id) REFERENCES language(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2519 (class 2606 OID 20436)
-- Name: language_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY category_lang
    ADD CONSTRAINT language_fk FOREIGN KEY (language_id) REFERENCES language(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2521 (class 2606 OID 20441)
-- Name: language_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY event_lang
    ADD CONSTRAINT language_fk FOREIGN KEY (language_id) REFERENCES language(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2524 (class 2606 OID 20446)
-- Name: language_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY order_delivery_lang
    ADD CONSTRAINT language_fk FOREIGN KEY (language_id) REFERENCES language(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2526 (class 2606 OID 20451)
-- Name: language_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY order_label_lang
    ADD CONSTRAINT language_fk FOREIGN KEY (language_id) REFERENCES language(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2537 (class 2606 OID 20456)
-- Name: language_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY product_lang
    ADD CONSTRAINT language_fk FOREIGN KEY (language_id) REFERENCES language(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2543 (class 2606 OID 20461)
-- Name: language_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY product_unit_lang
    ADD CONSTRAINT language_fk FOREIGN KEY (language_id) REFERENCES language(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2547 (class 2606 OID 20466)
-- Name: language_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY product_variant_lang
    ADD CONSTRAINT language_fk FOREIGN KEY (language_id) REFERENCES language(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2556 (class 2606 OID 20471)
-- Name: language_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY seo_lang
    ADD CONSTRAINT language_fk FOREIGN KEY (language_id) REFERENCES language(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2551 (class 2606 OID 20476)
-- Name: language_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY seo_category_lang
    ADD CONSTRAINT language_fk FOREIGN KEY (language_id) REFERENCES language(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2554 (class 2606 OID 20481)
-- Name: language_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY seo_dynamic_lang
    ADD CONSTRAINT language_fk FOREIGN KEY (language_id) REFERENCES language(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2558 (class 2606 OID 20486)
-- Name: language_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY service_lang
    ADD CONSTRAINT language_fk FOREIGN KEY (language_id) REFERENCES language(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2561 (class 2606 OID 20491)
-- Name: language_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY slider_image_lang
    ADD CONSTRAINT language_fk FOREIGN KEY (language_id) REFERENCES language(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2565 (class 2606 OID 20496)
-- Name: language_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY tax_group_lang
    ADD CONSTRAINT language_fk FOREIGN KEY (language_id) REFERENCES language(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2570 (class 2606 OID 20501)
-- Name: language_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY tax_option_lang
    ADD CONSTRAINT language_fk FOREIGN KEY (language_id) REFERENCES language(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2563 (class 2606 OID 20506)
-- Name: language_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY stock_lang
    ADD CONSTRAINT language_fk FOREIGN KEY (language_id) REFERENCES language(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2525 (class 2606 OID 20511)
-- Name: orders_delivery_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY order_delivery_lang
    ADD CONSTRAINT orders_delivery_fk FOREIGN KEY (order_delivery_id) REFERENCES order_delivery(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2527 (class 2606 OID 20516)
-- Name: orders_label_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY order_label_lang
    ADD CONSTRAINT orders_label_fk FOREIGN KEY (order_label_id) REFERENCES order_label(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2522 (class 2606 OID 20521)
-- Name: orders_orders_delivery_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "order"
    ADD CONSTRAINT orders_orders_delivery_id_fk FOREIGN KEY (delivery) REFERENCES order_delivery(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2523 (class 2606 OID 20526)
-- Name: orders_orders_label_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "order"
    ADD CONSTRAINT orders_orders_label_id_fk FOREIGN KEY (label) REFERENCES order_label(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2528 (class 2606 OID 20531)
-- Name: orders_products_orders_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY order_product
    ADD CONSTRAINT orders_products_orders_id_fk FOREIGN KEY (order_id) REFERENCES "order"(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2529 (class 2606 OID 20536)
-- Name: orders_products_product_variant_product_variant_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY order_product
    ADD CONSTRAINT orders_products_product_variant_product_variant_id_fk FOREIGN KEY (product_variant_id) REFERENCES product_variant(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2531 (class 2606 OID 20541)
-- Name: page_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY page_lang
    ADD CONSTRAINT page_fk FOREIGN KEY (page_id) REFERENCES page(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2532 (class 2606 OID 20546)
-- Name: product_brand_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY product
    ADD CONSTRAINT product_brand_fk FOREIGN KEY (brand_id) REFERENCES brand(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2538 (class 2606 OID 20551)
-- Name: product_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY product_lang
    ADD CONSTRAINT product_fk FOREIGN KEY (product_id) REFERENCES product(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2535 (class 2606 OID 20556)
-- Name: product_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY product_image
    ADD CONSTRAINT product_id_fk FOREIGN KEY (product_id) REFERENCES product(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2539 (class 2606 OID 20561)
-- Name: product_option_product_product_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY product_option
    ADD CONSTRAINT product_option_product_product_id_fk FOREIGN KEY (product_id) REFERENCES product(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2540 (class 2606 OID 20566)
-- Name: product_option_tax_option_tax_option_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY product_option
    ADD CONSTRAINT product_option_tax_option_tax_option_id_fk FOREIGN KEY (option_id) REFERENCES tax_option(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2541 (class 2606 OID 20576)
-- Name: product_stock_product_variant_product_variant_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY product_stock
    ADD CONSTRAINT product_stock_product_variant_product_variant_id_fk FOREIGN KEY (product_variant_id) REFERENCES product_variant(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2542 (class 2606 OID 20581)
-- Name: product_stock_stock_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY product_stock
    ADD CONSTRAINT product_stock_stock_id_fkey FOREIGN KEY (stock_id) REFERENCES stock(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2544 (class 2606 OID 20586)
-- Name: product_unit_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY product_unit_lang
    ADD CONSTRAINT product_unit_fk FOREIGN KEY (product_unit_id) REFERENCES product_unit(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2548 (class 2606 OID 20591)
-- Name: product_variant_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY product_variant_lang
    ADD CONSTRAINT product_variant_fk FOREIGN KEY (product_variant_id) REFERENCES product_variant(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2536 (class 2606 OID 20596)
-- Name: product_variant_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY product_image
    ADD CONSTRAINT product_variant_id_fkey FOREIGN KEY (product_variant_id) REFERENCES product_variant(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2549 (class 2606 OID 20601)
-- Name: product_variant_option_product_variant_product_variant_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY product_variant_option
    ADD CONSTRAINT product_variant_option_product_variant_product_variant_id_fk FOREIGN KEY (product_variant_id) REFERENCES product_variant(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2550 (class 2606 OID 20606)
-- Name: product_variant_option_tax_option_tax_option_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY product_variant_option
    ADD CONSTRAINT product_variant_option_tax_option_tax_option_id_fk FOREIGN KEY (option_id) REFERENCES tax_option(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2545 (class 2606 OID 20611)
-- Name: product_variant_product_product_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY product_variant
    ADD CONSTRAINT product_variant_product_product_id_fk FOREIGN KEY (product_id) REFERENCES product(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2546 (class 2606 OID 20616)
-- Name: product_variant_product_unit_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY product_variant
    ADD CONSTRAINT product_variant_product_unit_fkey FOREIGN KEY (product_unit_id) REFERENCES product_unit(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2552 (class 2606 OID 20621)
-- Name: seo_category_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY seo_category_lang
    ADD CONSTRAINT seo_category_fk FOREIGN KEY (seo_category_id) REFERENCES seo_category(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2553 (class 2606 OID 20626)
-- Name: seo_category_seo_dynamic_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY seo_dynamic
    ADD CONSTRAINT seo_category_seo_dynamic_fk FOREIGN KEY (seo_category_id) REFERENCES seo_category(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2555 (class 2606 OID 20631)
-- Name: seo_dynamic_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY seo_dynamic_lang
    ADD CONSTRAINT seo_dynamic_fk FOREIGN KEY (seo_dynamic_id) REFERENCES seo_dynamic(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2557 (class 2606 OID 20636)
-- Name: seo_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY seo_lang
    ADD CONSTRAINT seo_fk FOREIGN KEY (seo_id) REFERENCES seo(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2559 (class 2606 OID 20641)
-- Name: service_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY service_lang
    ADD CONSTRAINT service_fk FOREIGN KEY (service_id) REFERENCES service(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2562 (class 2606 OID 20646)
-- Name: slider_image_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY slider_image_lang
    ADD CONSTRAINT slider_image_fk FOREIGN KEY (slider_image_id) REFERENCES slider_image(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2560 (class 2606 OID 20651)
-- Name: slider_slider_image_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY slider_image
    ADD CONSTRAINT slider_slider_image_fk FOREIGN KEY (slider_id) REFERENCES slider(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2564 (class 2606 OID 20656)
-- Name: stock_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY stock_lang
    ADD CONSTRAINT stock_fk FOREIGN KEY (stock_id) REFERENCES stock(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2568 (class 2606 OID 20661)
-- Name: tax_group_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY tax_group_to_category
    ADD CONSTRAINT tax_group_fk FOREIGN KEY (tax_group_id) REFERENCES tax_group(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2566 (class 2606 OID 20666)
-- Name: tax_group_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY tax_group_lang
    ADD CONSTRAINT tax_group_fk FOREIGN KEY (tax_group_id) REFERENCES tax_group(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2571 (class 2606 OID 20671)
-- Name: tax_option_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY tax_option_lang
    ADD CONSTRAINT tax_option_fk FOREIGN KEY (tax_option_id) REFERENCES tax_option(id) ON UPDATE CASCADE ON DELETE CASCADE;


-- Completed on 2016-10-31 16:40:16 EET

--
-- PostgreSQL database dump complete
--

