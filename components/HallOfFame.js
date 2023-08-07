import PageBuilder from "./PageBuilder.js";

export default class HallOfFame {
    /**
     * 
     * @param HTMLElement : root container in which the modal will open
     */
    constructor(root) {
        this.container = root;

        this.createModal();
        this.initCloseBtn();
        this.createChart();
    }
    createModal() {
        const template = {
            tag: "div",
            attrs: {
                class: "wins-modal"
            },
            children: [
                {
                    tag: "canvas",
                    attrs: {
                        id: "wins-chart"
                    }
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

    initCloseBtn() {
        this.closeBtn = this.element.querySelector(".modal-close");
        this.closeBtn.addEventListener("click", () => {
            this.element.classList.remove('show');
            setTimeout(() => {
                this.element.remove();
            }, 500);
        })
    }

    async getData() {
        const wins = await fetch('./api/v1/fights/number-of-wins')
            .then(response => response.json())
            .then(data => data);

        const data = {
            names: wins.map((rec) => rec.name),
            wins: wins.map((rec) => rec.wins),
        }
        console.log(wins, data);
        return data;
    }
    async createChart() {
        this.chart = this.element.querySelector("#wins-chart");
        const data = await this.getData();
        console.log(data);
        new Chart(this.chart, {
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
                        beginAtZero: true
                    }
                },
                plugins: {
                    decimation: {
                        enabled: false
                    }
                }
            }
        });
    }
}