<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Print Receipt</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/vfs_fonts.js"></script>
    <script src="qrious.min.js"></script>
    <link rel="icon" type="image/x-icon" href="./images/super-express-cargo.ico">

    
  </head>
  <body>

    <script>
      
      // Get the document ID from the URL
      const params = new URLSearchParams(window.location.search);
      const documentId = params.get("id");

      // Fetch the shipment data using PHP
      fetch(`fetch_shipment.php?id=${documentId}`)
        .then((response) => response.json())
        .then((shipmentData) => {
          if (shipmentData) {
            const formattedDate = moment(shipmentData["date"]).format("DD-MM-YYYY");

            // Create the QR code
            const qr = new QRious({
              element: document.getElementById("qrcode"),
              value: `${shipmentData["receipt_no"]}, Thank you for choosing Super Express`, // Use the receipt number from shipmentData
            });

            // Create the PDF document definition
            const docDefinition = {
              content: [
                {
                  columns: [
                    {
                      width: 'auto',
                      text: "Super Express",
                      alignment: "left",
                      bold: true,
                      fontSize: 16,

                    },
                    {
                      width: '*',
                      stack: [
                        {
                          text: "Dispatch Date",
                          // style: "header",
                          alignment: "center",
                          fontSize: 14,
                          bold: true,


                        },
                        {
                          text: formattedDate,
                          alignment: "center",
                          bold: true,
                          fontSize: 13,
                        },
                      ],
                    },
                    {
                      width: 'auto',
                      text: "Shipment Receipt",
                      alignment: "right",
                      bold:true,
                    },
                  ],
                },
                {
                  table: {
                    widths: ["*", "*", "*"],
                    body: [
                      [
                        {
                          text: "Shipper / Consignee Info",
                          style: "tableHeader",
                          alignment: "center",
                          margin: [0, 5, 0, 5],
                        },
                        {
                          text: "Receipt Number",
                          style: "tableHeader",
                          alignment: "center",
                          margin: [0, 5, 0, 5],
                        },
                        {
                          text: "Shipment Info",
                          style: "tableHeader",
                          alignment: "center",
                          margin: [0, 5, 0, 5],
                        },
                      ],
                      [
                        {
                          stack: [
                            {
                              text: "Consignee Information",
                              alignment: "center",
                              decoration: "underline",
                              margin: [0, 5, 0, 0],
                            },
                            {
                              text: `\nName:     ${shipmentData["consignee_name"]}`,
                              alignment: "left",
                            },
                            {
                              text: `\nContact:  ${shipmentData["consignee_contact"]}\n\n`,
                              alignment: "left",
                            },
                            {
                              text: "Shipper Information",
                              alignment: "center",
                              decoration: "underline",
                              margin: [0, 10, 0, 0],
                            },
                            {
                              text: `\nName:     ${shipmentData["shipper_name"]}`,
                              alignment: "left",
                            },
                            {
                              text: `\nContact:  ${shipmentData["shipper_contact"]}\n\n`,
                              alignment: "left",
                            },
                            {
                              text: `\nSignature:__________________`,
                              alignment: "left",
                              bold:true,
                            },
                          ],
                          alignment: "left",
                        },
                        {
                          stack: [
                            {
                              text: `\n${shipmentData["receipt_no"].toUpperCase()}`,
                              alignment: "center",
                              margin: [0, 5, 0, 30],
                              bold: true,
                              fontSize: "11",
                            },
                            {
                              image: qr.toDataURL(),
                              fit: [70, 70],
                              alignment: "center",
                            },
                            {
                              text: `\nDestination:`,
                              alignment: "center",
                              bold: true,
                              fontSize: "13",
                            },
                            {
                              text: `${shipmentData["destination"]}`,
                              alignment: "center",
                              fontSize: "16",
                            },
                          ],
                        },
                        {
                          table: {
                            widths: ["*", "*"],
                            heights: [20, 20, 20, 20, 20, 20, 20, 20], // Set the desired height for each row
                            body: [
                              [
                                { text: "Origin:", bold: true },
                                {
                                  text: shipmentData["origin"],
                                  alignment: "left",
                                },
                              ],
                              [
                                { text: "Weight:", bold: true },
                                {
                                  text: shipmentData["weight"],
                                  alignment: "left",
                                },
                              ],
                              [
                                { text: "Pcs:", bold: true },
                                {
                                  text: shipmentData["pieces"],
                                  alignment: "left",
                                },
                              ],
                              [
                                { text: "Mode of Payment:", bold: true },
                                {
                                  text: shipmentData["mode_of_payment"],
                                  alignment: "left",
                                },
                              ],
                              [
                                { text: "Rate:", bold: true },
                                {
                                  text: shipmentData["rate"],
                                  alignment: "left",
                                },
                              ],
                              [
                                { text: "Packing:", bold: true },
                                {
                                  text: shipmentData["packing"],
                                  alignment: "left",
                                },
                              ],
                              [
                                { text: "Local Charges:", bold: true },
                                {
                                  text: shipmentData["local_charges"],
                                  alignment: "left",
                                },
                              ],
                              [
                                { text: "Total:", bold: true },
                                {
                                  text: shipmentData["total_amount"],
                                  alignment: "left",
                                  bold: true,
                                },
                              ],
                            ],
                          },
                          layout: {
                            defaultBorder: false,
                            hLineWidth: function () {
                              return 0;
                            },
                            vLineWidth: function () {
                              return 0;
                            },
                          },
                        },
                      ],
                      [
                        {
                          text: `Main Karachi Office: G-56, Deans Market, Main Tariq Road, Karachi\nContact: 0321-9285851, 0321-8756687\nThank you for choosing Super Express!`,
                          alignment: "center",
                          colSpan: 3, // Span the entire width of the table
                        },
                      ],
                    ],
                  },
                  layout: {
                    hLineWidth: function (i, node) {
                      return i === 0 || i === node.table.body.length ? 1 : 1;
                    },
                    vLineWidth: function (i, node) {
                      return i === 0 || i === node.table.widths.length ? 1 : 1;
                    },
                    paddingTop: function (i) {
                      return i === 2 ? 5 : 0;
                    },
                    paddingBottom: function (i) {
                      return i === 2 ? 5 : 0;
                    },
                  },
                  margin: [0, 20, 0, 15],
                },
                {
                  text: "-----------------------------------------------------------------------------------------------------------------------------------------------------------",
                  margin: [0, 20, 0, 15],
                },
                {
                  columns: [
                    {
                      width: 'auto',
                      text: "Super Express",
                      alignment: "left",
                      bold: true,
                      fontSize: 16,
                    },
                    {
                      width: '*',
                      stack: [
                        {
                          text: "Dispatch Date",
                          // style: "header",
                          alignment: "center",
                          fontSize: 14,
                          bold: true,


                        },
                        {
                          text: formattedDate,
                          alignment: "center",
                          bold: true,
                          fontSize: 13,
                        },
                      ],
                    },
                    {
                      width: 'auto',
                      text: "Shipment Receipt",
                      alignment: "right",
                      bold:true,
                    },
                  ],
                },
                {
                  table: {
                    widths: ["*", "*", "*"],
                    body: [
                      [
                        {
                          text: "Shipper / Consignee Info",
                          style: "tableHeader",
                          alignment: "center",
                          margin: [0, 5, 0, 5],
                        },
                        {
                          text: "Receipt Number",
                          style: "tableHeader",
                          alignment: "center",
                          margin: [0, 5, 0, 5],
                        },
                        {
                          text: "Shipment Info",
                          style: "tableHeader",
                          alignment: "center",
                          margin: [0, 5, 0, 5],
                        },
                      ],
                      [
                        {
                          stack: [
                            {
                              text: "Consignee Information",
                              alignment: "center",
                              decoration: "underline",
                              margin: [0, 5, 0, 0],
                            },
                            {
                              text: `\nName:     ${shipmentData["consignee_name"]}`,
                              alignment: "left",
                            },
                            {
                              text: `\nContact:  ${shipmentData["consignee_contact"]}\n\n`,
                              alignment: "left",
                            },
                            {
                              text: "Shipper Information",
                              alignment: "center",
                              decoration: "underline",
                              margin: [0, 10, 0, 0],
                            },
                            {
                              text: `\nName:     ${shipmentData["shipper_name"]}`,
                              alignment: "left",
                            },
                            {
                              text: `\nContact:  ${shipmentData["shipper_contact"]}\n\n`,
                              alignment: "left",
                            },
                            {
                              text: `\nSignature:__________________`,
                              alignment: "left",
                              bold:true,
                            },
                          ],
                          alignment: "left",
                        },
                        {
                          stack: [
                            {
                              text: `\n${shipmentData["receipt_no"].toUpperCase()}`,
                              alignment: "center",
                              margin: [0, 5, 0, 30],
                              bold: true,
                              fontSize: "11",
                            },
                            {
                              image: qr.toDataURL(),
                              fit: [70, 70],
                              alignment: "center",
                            },
                            {
                              text: `\nDestination:`,
                              alignment: "center",
                              bold: true,
                              fontSize: "13",
                            },
                            {
                              text: `${shipmentData["destination"]}`,
                              alignment: "center",
                              fontSize: "16",
                            },
                          ],
                        },
                        {
                          table: {
                            widths: ["*", "*"],
                            heights: [20, 20, 20, 20, 20, 20, 20, 20], // Set the desired height for each row
                            body: [
                              [
                                { text: "Origin:", bold: true },
                                {
                                  text: shipmentData["origin"],
                                  alignment: "left",
                                },
                              ],
                              [
                                { text: "Weight:", bold: true },
                                {
                                  text: shipmentData["weight"],
                                  alignment: "left",
                                },
                              ],
                              [
                                { text: "Pcs:", bold: true },
                                {
                                  text: shipmentData["pieces"],
                                  alignment: "left",
                                },
                              ],
                              [
                                { text: "Mode of Payment:", bold: true },
                                {
                                  text: shipmentData["mode_of_payment"],
                                  alignment: "left",
                                },
                              ],
                              [
                                { text: "Rate:", bold: true },
                                {
                                  text: shipmentData["rate"],
                                  alignment: "left",
                                },
                              ],
                              [
                                { text: "Packing:", bold: true },
                                {
                                  text: shipmentData["packing"],
                                  alignment: "left",
                                },
                              ],
                              [
                                { text: "Local Charges:", bold: true },
                                {
                                  text: shipmentData["local_charges"],
                                  alignment: "left",
                                },
                              ],
                              [
                                { text: "Total:", bold: true },
                                {
                                  text: shipmentData["total_amount"],
                                  alignment: "left",
                                  bold: true,
                                },
                              ],
                            ],
                          },
                          layout: {
                            defaultBorder: false,
                            hLineWidth: function () {
                              return 0;
                            },
                            vLineWidth: function () {
                              return 0;
                            },
                          },
                        },
                      ],
                      [
                        {
                          text: `Main Karachi Office: G-56, Deans Market, Main Tariq Road, Karachi\nContact: 0321-9285851, 0321-8756687\nThank you for choosing Super Express!`,
                          alignment: "center",
                          colSpan: 3, // Span the entire width of the table
                        },
                      ],
                    ],
                  },
                  layout: {
                    hLineWidth: function (i, node) {
                      return i === 0 || i === node.table.body.length ? 1 : 1;
                    },
                    vLineWidth: function (i, node) {
                      return i === 0 || i === node.table.widths.length ? 1 : 1;
                    },
                    paddingTop: function (i) {
                      return i === 2 ? 5 : 0;
                    },
                    paddingBottom: function (i) {
                      return i === 2 ? 5 : 0;
                    },
                  },
                  margin: [0, 20, 0, 10],
                },
              ],
              styles: {
                header: {
                  fontSize: 18,
                  bold: true,
                },
                tableHeader: {
                  bold: true,
                  fontSize: 12,
                  color: "black",
                },
              },
              defaultStyle: {},
            };

            // Generate the PDF document
            const pdfDocGenerator = pdfMake.createPdf(docDefinition);

            // Open the PDF document in a new tab as a print prompt
            pdfDocGenerator.getBlob((blob) => {
    const url = URL.createObjectURL(blob);
    const printWindow = window.open(url, "_self"); // Open in a new tab
    printWindow.onload = () => {
        printWindow.print();
        printWindow.onafterprint = () => {
            URL.revokeObjectURL(url);
            printWindow.close();
        };
    };
});
          } else {
            console.log("Document does not exist");
          }
        })
        .catch((error) => {
          console.error("Error fetching data: ", error);
        });
    </script>
  </body>
</html>
