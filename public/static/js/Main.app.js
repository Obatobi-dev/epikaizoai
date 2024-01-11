$(() => {
    // Prevent form submission
    $("form").submit(e => {
        e.preventDefault()
    })

    $("[data-insert]").each((index, self) => {
        let func = eval(self.dataset.insert) ?? ''

        // Type check if input or html elem
        if(self.firstChild == null){
            // An input element
            // Mainpulat it value
            self.value = func
            return;
        }

        $(self).html(func)
    })

    // Disable link if the href of the link is same as the one in the url bar
    let HREF = location.href
    $("a").each((index, elem, event, ty) => {
        if(elem.href === HREF){
            elem.onclick = (event)=> {
                event.preventDefault()
            }
        }
    })
})