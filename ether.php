<html>
    <head>NFT</head>
    <body>
        <h1>Call NFT with Ether for Ticketly</h1>
<div class="container">
  <center>
  <h2>Upload to IPFS</h2>
  <input id="fileUpload" type="file" class="btn btn-primary">Select File</input>
  <br /><br />
  <div id="ipfs_hash"></div>
  </center>
</div>

        <a href="javascript:mintMeta()">
            Mint NFT</a>
    </body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://unpkg.com/ipfs-http-client/dist/index.min.js"></script>
<script src="https://cdn.ethers.io/lib/ethers-5.2.umd.min.js" type="application/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/@biconomy/mexa@latest/dist/mexa.js"></script>

<script>


function uploadToIPFS() {
    if(!window.IpfsHttpClient) return;
    var ipfs = window.IpfsHttpClient({ host: 'ipfs.infura.io', port: 5001 });
    document.getElementById("ipfs_hash").innerHTML = "<b>Uploading...Please wait</b>"
    
    var reader = new FileReader();
    reader.onload = function() {

        var arrayBuffer = this.result,
        array = new Uint8Array(arrayBuffer),
        binaryString = String.fromCharCode.apply(null, array);

        //console.log(result)
        ipfs.add(binaryString, (err, result) => {
        console.log(result)
        document.getElementById("ipfs_hash").innerHTML = "<b>IPFS Hash of uploded file: </b>"+result[0].hash 
        })
    }
    reader.readAsArrayBuffer(this.files[0]);
}

    
    document.querySelector('#fileUpload').addEventListener('change', function() {
        uploadToIPFS();
    }, false);


</script>

<script>
    var userAddress="";
    var nftContractAddress = "0x417a3863b1aae2F3bdDF37a22D5e24A248C9b539";
    var contract;
    var biconomy;
    var abi = [
    {
      "inputs": [
        {
          "internalType": "address",
          "name": "_trustedForwarder",
          "type": "address"
        }
      ],
      "stateMutability": "nonpayable",
      "type": "constructor"
    },
    {
      "anonymous": false,
      "inputs": [
        {
          "indexed": true,
          "internalType": "address",
          "name": "owner",
          "type": "address"
        },
        {
          "indexed": true,
          "internalType": "address",
          "name": "approved",
          "type": "address"
        },
        {
          "indexed": true,
          "internalType": "uint256",
          "name": "tokenId",
          "type": "uint256"
        }
      ],
      "name": "Approval",
      "type": "event"
    },
    {
      "anonymous": false,
      "inputs": [
        {
          "indexed": true,
          "internalType": "address",
          "name": "owner",
          "type": "address"
        },
        {
          "indexed": true,
          "internalType": "address",
          "name": "operator",
          "type": "address"
        },
        {
          "indexed": false,
          "internalType": "bool",
          "name": "approved",
          "type": "bool"
        }
      ],
      "name": "ApprovalForAll",
      "type": "event"
    },
    {
      "anonymous": false,
      "inputs": [
        {
          "indexed": true,
          "internalType": "address",
          "name": "from",
          "type": "address"
        },
        {
          "indexed": true,
          "internalType": "address",
          "name": "to",
          "type": "address"
        },
        {
          "indexed": true,
          "internalType": "uint256",
          "name": "tokenId",
          "type": "uint256"
        }
      ],
      "name": "Transfer",
      "type": "event"
    },
    {
      "inputs": [
        {
          "internalType": "address",
          "name": "to",
          "type": "address"
        },
        {
          "internalType": "uint256",
          "name": "tokenId",
          "type": "uint256"
        }
      ],
      "name": "approve",
      "outputs": [],
      "stateMutability": "nonpayable",
      "type": "function"
    },
    {
      "inputs": [
        {
          "internalType": "address",
          "name": "owner",
          "type": "address"
        }
      ],
      "name": "balanceOf",
      "outputs": [
        {
          "internalType": "uint256",
          "name": "",
          "type": "uint256"
        }
      ],
      "stateMutability": "view",
      "type": "function",
      "constant": true
    },
    {
      "inputs": [
        {
          "internalType": "uint256",
          "name": "tokenId",
          "type": "uint256"
        }
      ],
      "name": "getApproved",
      "outputs": [
        {
          "internalType": "address",
          "name": "",
          "type": "address"
        }
      ],
      "stateMutability": "view",
      "type": "function",
      "constant": true
    },
    {
      "inputs": [
        {
          "internalType": "address",
          "name": "owner",
          "type": "address"
        },
        {
          "internalType": "address",
          "name": "operator",
          "type": "address"
        }
      ],
      "name": "isApprovedForAll",
      "outputs": [
        {
          "internalType": "bool",
          "name": "",
          "type": "bool"
        }
      ],
      "stateMutability": "view",
      "type": "function",
      "constant": true
    },
    {
      "inputs": [
        {
          "internalType": "address",
          "name": "forwarder",
          "type": "address"
        }
      ],
      "name": "isTrustedForwarder",
      "outputs": [
        {
          "internalType": "bool",
          "name": "",
          "type": "bool"
        }
      ],
      "stateMutability": "view",
      "type": "function",
      "constant": true
    },
    {
      "inputs": [],
      "name": "name",
      "outputs": [
        {
          "internalType": "string",
          "name": "",
          "type": "string"
        }
      ],
      "stateMutability": "view",
      "type": "function",
      "constant": true
    },
    {
      "inputs": [
        {
          "internalType": "uint256",
          "name": "tokenId",
          "type": "uint256"
        }
      ],
      "name": "ownerOf",
      "outputs": [
        {
          "internalType": "address",
          "name": "",
          "type": "address"
        }
      ],
      "stateMutability": "view",
      "type": "function",
      "constant": true
    },
    {
      "inputs": [
        {
          "internalType": "address",
          "name": "from",
          "type": "address"
        },
        {
          "internalType": "address",
          "name": "to",
          "type": "address"
        },
        {
          "internalType": "uint256",
          "name": "tokenId",
          "type": "uint256"
        }
      ],
      "name": "safeTransferFrom",
      "outputs": [],
      "stateMutability": "nonpayable",
      "type": "function"
    },
    {
      "inputs": [
        {
          "internalType": "address",
          "name": "from",
          "type": "address"
        },
        {
          "internalType": "address",
          "name": "to",
          "type": "address"
        },
        {
          "internalType": "uint256",
          "name": "tokenId",
          "type": "uint256"
        },
        {
          "internalType": "bytes",
          "name": "_data",
          "type": "bytes"
        }
      ],
      "name": "safeTransferFrom",
      "outputs": [],
      "stateMutability": "nonpayable",
      "type": "function"
    },
    {
      "inputs": [
        {
          "internalType": "address",
          "name": "operator",
          "type": "address"
        },
        {
          "internalType": "bool",
          "name": "approved",
          "type": "bool"
        }
      ],
      "name": "setApprovalForAll",
      "outputs": [],
      "stateMutability": "nonpayable",
      "type": "function"
    },
    {
      "inputs": [
        {
          "internalType": "bytes4",
          "name": "interfaceId",
          "type": "bytes4"
        }
      ],
      "name": "supportsInterface",
      "outputs": [
        {
          "internalType": "bool",
          "name": "",
          "type": "bool"
        }
      ],
      "stateMutability": "view",
      "type": "function",
      "constant": true
    },
    {
      "inputs": [],
      "name": "symbol",
      "outputs": [
        {
          "internalType": "string",
          "name": "",
          "type": "string"
        }
      ],
      "stateMutability": "view",
      "type": "function",
      "constant": true
    },
    {
      "inputs": [],
      "name": "tokenCount",
      "outputs": [
        {
          "internalType": "uint256",
          "name": "_value",
          "type": "uint256"
        }
      ],
      "stateMutability": "view",
      "type": "function",
      "constant": true
    },
    {
      "inputs": [
        {
          "internalType": "uint256",
          "name": "tokenId",
          "type": "uint256"
        }
      ],
      "name": "tokenURI",
      "outputs": [
        {
          "internalType": "string",
          "name": "",
          "type": "string"
        }
      ],
      "stateMutability": "view",
      "type": "function",
      "constant": true
    },
    {
      "inputs": [
        {
          "internalType": "address",
          "name": "from",
          "type": "address"
        },
        {
          "internalType": "address",
          "name": "to",
          "type": "address"
        },
        {
          "internalType": "uint256",
          "name": "tokenId",
          "type": "uint256"
        }
      ],
      "name": "transferFrom",
      "outputs": [],
      "stateMutability": "nonpayable",
      "type": "function"
    },
    {
      "inputs": [
        {
          "internalType": "string",
          "name": "_tokenURI",
          "type": "string"
        }
      ],
      "name": "mint",
      "outputs": [
        {
          "internalType": "uint256",
          "name": "",
          "type": "uint256"
        }
      ],
      "stateMutability": "nonpayable",
      "type": "function"
    }
  ];
    async function init () {
    if (typeof window.ethereum !== "undefined") {
      const accounts = await window.ethereum.request({
        method: "eth_requestAccounts",
      });
      console.log('accounts',accounts);
      userAddress = accounts[0];

      // We're creating biconomy provider linked to your network of choice where your contract is deployed
      // Import Biconomy
    var Biconomy = window.Biconomy;//.default;
    console.log('Biconomy',Biconomy);
    
    biconomy = new Biconomy(window.ethereum,
        {
          apiKey: "6M7gBvOPV.109d98d5-71ba-4744-af89-4c73808a6c02",
          debug: true,
        }
      );
      console.log('Biconomy1',biconomy);
      
      
      biconomy
        .onEvent(biconomy.READY, async () => {
            console.log("ready biconomy")
          // Initialize your dapp here like getting user accounts etc
           contract = new ethers.Contract(
             nftContractAddress,
             abi,
             biconomy.getSignerByAddress(userAddress)
           );
           console.log("contract",contract)

        //   // Handle error while initializing mexa
           contractInterface = new ethers.utils.Interface(abi);
        })
        .onEvent(biconomy.ERROR, (error, message) => {
          console.log("biconomy.ERROR 1", message);
          console.log("biconomy.ERROR 2", error);
        });//*/
      
    } else {
      console.log("Wallet not found");
      alert("please enable wallet");
    }
  };

  var gasless=1;
  async  function mintMeta (){
    try {
      var ethereum = window.ethereum;
      console.log("ethereum",ethereum)
      if (ethereum) {
        //If we want to take free gas from Third party like biconomy for NFT mint
        if (gasless === 1) {
          
        var mintData = "QmbXnbtZPHLDgcBHMaBZh8Sto5KDN7H4XSJK4K7LkgHpWW";
         console.log('mintData',mintData);
          let { data } = await contract.populateTransaction.mint(mintData);
          console.log('data',data);
          let provider = biconomy.getEthersProvider();

          let gasLimit = await provider.estimateGas({
            to: nftContractAddress,
            from: userAddress,
            data: data,
          });

          let txParams = {
            data: data,
            to: nftContractAddress,
            from: userAddress,
            gasLimit: 10000000,
            signatureType: "EIP712_SIGN",
          };

          console.log({txParams});

          let tx;

          try {
            tx = await provider.send("eth_sendTransaction", [txParams]);
          } catch (err) {
            console.log("handle errors like signature denied here");
            console.log(err);
          }

          console.log("Transaction hash : ", tx);

          provider.once(tx, (transaction) => {
            console.log("Transaction",transaction.transactionHash);
          });
        } else {
          const tx = await contract.createEternalNFT();
          await tx.wait();
        }
      } else {
        console.log("Ethereum object doesn't exist!");
      }
    } catch (error) {}
  }

  $(function(){
     //init();
  });
</script>

