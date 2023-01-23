import html2canvas from "html2canvas";
import { elementToSVG } from 'dom-to-svg'
import { Notyf } from 'notyf';
import 'notyf/notyf.min.css';
const notyf = new Notyf({
    types: [
        {
            type: 'info',
            background: '#302f82',
            icon: false
        }
    ]
});

if (document.querySelector(".download-qr")) {
    let wrapper = document.querySelector(".download-qr");
    let filename = wrapper.dataset.filename;
    let qrCode = document.querySelector(`#${filename}`)
    document.querySelectorAll(".download-qr a").forEach((button) => {
        let type = button.dataset.type;
        let mime = button.dataset.mime;
        button.addEventListener("click", (e) => {
            e.preventDefault();
            html2canvas(qrCode).then((canvas) => {
                let a = document.createElement("a");
                switch (type) {
                    case "png":
                    case "jpeg":
                        a.href = canvas.toDataURL(mime);
                        break;
                    case "svg":
                        let svg = elementToSVG(qrCode);
                        let svgString = new XMLSerializer().serializeToString(svg);
                        a.href = `data:image/svg+xml;utf8,` + encodeURIComponent(svgString);
                        break;
                }
                a.download = `${filename}.${type}`;
                a.click();
            });
        });
    });
}

document.getElementById("copy-link").addEventListener("click", function (e) {
    e.preventDefault();
    notyf.open({
        type: 'info',
        message: 'Link copied to clipboard',
        duration: 8000,
        dismissible: true,
        position: {
            x: 'center',
            y: 'top',
        },
    });
    navigator.clipboard.writeText(this.href);
});