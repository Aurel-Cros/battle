export default class PageBuilder {
    /**
     * Creates a DOM tree from a JS object literal.
     * Tag is mandatory, other parameters are optional.
     * Use tag: "TEXTNODE" and the content property to insert text a node.
     * @param {Object} structure
     * {
            tag: "tag name",
            attrs: {
                attribute: "value"
            },
            content: "Text"
            children: [{},...]
        }
     */
    constructor(structure) {
        if (Array.isArray(structure))
            throw new Exception('Wrong format');

        const _parent = this.makeOne(structure);
        return _parent
    }

    makeOne(structure) {
        if (structure.tag === 'TEXTNODE')
            return structure.content;

        const newElement = document.createElement(structure.tag);

        for (const attribute in structure.attrs) {
            newElement.setAttribute(attribute, structure.attrs[attribute]);
        }
        newElement.textContent = structure.content || '';

        structure.children?.forEach((child) => {
            newElement.append(this.makeOne(child));
        })

        return newElement;
    }
}