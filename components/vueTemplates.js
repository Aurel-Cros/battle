const vueTemplates =
{
    match: {
        tag: 'div',
        attrs: {
            class: "row gx-5 gap-3",
            id: "match"
        },
        children: [
            {
                tag: "h2",
                content: "Match"
            },
            {
                tag: "div",
                attrs: {
                    class: "col-6 row mt-3 justify-content-end"
                },
                children: [
                    {
                        tag: "div",
                        attrs: {
                            class: "pt-1 position-relative d-flex flex-column align-items-end w-75"
                        },
                        children: [
                            {
                                tag: "img",
                                attrs: {
                                    id: "avatar-player1",
                                    class: "avatar float-end mt-2",
                                    alt: "Avatar"
                                }
                            }
                            ,
                            {
                                tag: "span",
                                attrs: {
                                    style: "width: 100%",
                                    class: "position-absolute top-0 end-0 translate-middle-y badge rounded-pill bg-transparent border border-2 border-danger"
                                },
                                content: " "
                            }
                            ,
                            {
                                tag: "span",
                                attrs: {
                                    style: "width: 100%",
                                    id: "hp-player1",
                                    class: "position-absolute top-0 end-0 translate-middle-y badge rounded-pill bg-danger"
                                },
                                content: " "
                            },
                            {
                                tag: "ul",
                                children: [
                                    {
                                        tag: "li",
                                        attrs: {
                                            id: "name-player1"
                                        }
                                    },
                                    {
                                        tag: "li",
                                        attrs: {
                                            id: "attack-player1"
                                        }
                                    },
                                    {
                                        tag: "li",
                                        attrs: {
                                            id: "mana-player1"
                                        }
                                    },
                                ]
                            }
                        ]
                    }
                ]
            },
            {
                tag: "div",
                attrs: {
                    id: "opponent",
                    class: "col-6 row mt-3 justify-content-start"
                },
                children: [
                    {
                        tag: "div",
                        attrs: {
                            class: "pt-1 position-relative d-flex flex-column align-items-start w-75"
                        },
                        children: [
                            {
                                tag: "img",
                                attrs: {
                                    id: "avatar-player2",
                                    class: "avatar mt-2",
                                    alt: "Avatar"
                                }
                            }
                            ,
                            {
                                tag: "span",
                                attrs: {
                                    style: "width: 100%",
                                    class: "position-absolute top-0 start-0 translate-middle-y badge rounded-pill bg-transparent border border-2 border-danger"
                                },
                                content: " "
                            }
                            ,
                            {
                                tag: "span",
                                attrs: {
                                    style: "width: 100%",
                                    id: "hp-player2",
                                    class: "position-absolute top-0 start-0 translate-middle-y badge rounded-pill bg-danger"
                                },
                                content: " "
                            },
                            {
                                tag: "ul",
                                children: [
                                    {
                                        tag: "li",
                                        attrs: {
                                            id: "name-player2"
                                        }
                                    },
                                    {
                                        tag: "li",
                                        attrs: {
                                            id: "attack-player2"
                                        }
                                    },
                                    {
                                        tag: "li",
                                        attrs: {
                                            id: "mana-player2"
                                        }
                                    },
                                ]
                            }
                        ]
                    }
                ]
            },
            {
                tag: "div",
                attrs: {
                    id: "combats"
                },
                children: [
                    {
                        tag: "h2",
                        content: "Combat"
                    },
                    {
                        tag: "div",
                        attrs: {
                            class: "controls d-flex justify-content-center"
                        },
                        children: [
                            {
                                tag: "input",
                                attrs: {
                                    type: "button",
                                    value: "Attaquer",
                                    id: "attack",
                                    class: "btn btn-outline-secondary"
                                }
                            },
                            {
                                tag: "input",
                                attrs: {
                                    type: "button",
                                    value: "Se soigner",
                                    id: "heal",
                                    class: "btn btn-outline-secondary"
                                }
                            },
                            {
                                tag: "input",
                                attrs: {
                                    type: "button",
                                    value: "Stopper le combat",
                                    id: "restart",
                                    class: "btn btn-outline-secondary"
                                }
                            }
                        ]
                    },
                    {
                        tag: "ul",
                        attrs: {
                            class: "battleLog"
                        }
                    }
                ]
            }
        ]
    },
    result: {
        tag: 'div',
        attrs: {
            id: 'Results'
        },
        children: [
            {
                tag: 'h2',
                content: 'Résultat'
            },
            {
                tag: 'div',
                attrs: {
                    class: 'd-flex justify-content-center'
                },
                children: [
                    {
                        tag: 'input',
                        attrs: {
                            value: 'Nouveau combat',
                            type: 'button',
                            class: 'btn btn-outline-secondary'
                        }
                    }
                ]
            }
        ]
    }
}

export default vueTemplates