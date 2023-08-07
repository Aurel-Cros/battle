import PageBuilder from "./PageBuilder.js";

export default class HallOfFame {
    /**
     * 
     * @param HTMLElement : root container in which the modal will open
     */
    constructor(root) {
        this.container = root;

        this.createModal();
        this.chartContainer = this.element.querySelector("#chart");
        this.initCloseBtn();
        this.initView();
    }
    async initView() {
        await this.createWinsChart();
        this.initLinks();
    }
    createModal() {
        const template = {
            tag: "div",
            attrs: {
                class: "wins-modal"
            },
            children: [
                {
                    tag: "div",
                    children: [
                        {
                            tag: "h2",
                            content: "Hall of Fame",
                            attrs: {
                                class: "text-center"
                            }
                        },
                        {
                            tag: "p",
                            content: "and statistics",
                            attrs: {
                                class: "text-center"
                            }
                        },
                    ]
                },
                {
                    tag: "nav",
                    attrs: {
                        class: "d-flex gap-3 justify-content-center"
                    },
                    children: [
                        {
                            tag: "a",
                            content: "Wins",
                            attrs: {
                                href: "#",
                                class: "link-to-ws"
                            }
                        },
                        {
                            tag: "a",
                            content: "Losses",
                            attrs: {
                                href: "#",
                                class: "link-to-ls"
                            }
                        }
                    ]
                }
                ,
                {
                    tag: "div",
                    attrs: {
                        class: "w-100",
                        style: "height: 400px"
                    },
                    children: [
                        {
                            tag: "canvas",
                            attrs: {
                                id: "chart"
                            }
                        }
                    ]
                },
                {
                    tag: "button",
                    attrs: {
                        class: "modal-close btn btn-outline-secondary"
                    },
                    content: "Fermer"
                }
            ]
        }
        this.element = new PageBuilder(template);
        this.container.append(this.element);
        setTimeout(() => {
            this.element.classList.add('show');
        }, 5);
    }
    initLinks() {
        this.element.querySelector(".link-to-ws").addEventListener("click", (e) => {
            e.preventDefault();
            this.clearChart();
            this.createWinsChart();
        })
        this.element.querySelector(".link-to-ls").addEventListener("click", (e) => {
            e.preventDefault();
            this.clearChart();
            this.createLossesChart();
        })
    }
    initCloseBtn() {
        this.closeBtn = this.element.querySelector(".modal-close");
        this.closeBtn.addEventListener("click", () => {
            this.element.classList.remove('show');
            setTimeout(() => {
                this.element.remove();
            }, 500);
        })
    }

    async getData(type) {
        switch (type) {

            case 'wins':
                const wins = await fetch('./api/v1/fights/number-of-wins')
                    .then(response => response.json())
                    .then(data => data);

                const dataW = {
                    names: wins.map((rec) => rec.name),
                    wins: wins.map((rec) => rec.wins),
                }
                return dataW;

            case 'losses':
                const losses = await fetch('./api/v1/fights/number-of-losses')
                    .then(response => response.json())
                    .then(data => data);

                const dataL = {
                    names: losses.map((rec) => rec.name),
                    losses: losses.map((rec) => rec.losses),
                }
                return dataL;
        }
    }
    async createWinsChart() {
        const data = await this.getData('wins');
        this.chart = new Chart(this.chartContainer, {
            type: 'bar',
            data: {
                labels: data.names,
                datasets: [{
                    label: '# of wins',
                    data: data.wins,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
        return true;
    }
    async createLossesChart() {
        const data = await this.getData('losses');

        this.chart = new Chart(this.chartContainer, {
            type: 'bar',
            data: {
                labels: data.names,
                datasets: [{
                    label: '# of losses',
                    data: data.losses,
                    borderWidth: 1,
                    backgroundColor: '#FF9191'
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }
    clearChart() {
        this.chart.destroy();
    }
}