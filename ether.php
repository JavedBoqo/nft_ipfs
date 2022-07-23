<html>
    <head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    </head>
    <body>
        <div class="text-center"><h2>Call NFT with Ether for Ticketly</h2></div>
<div class="container">
        <form>
        <div style="display: flex;flex-direction: column;">      
            <label class="form-label">Upload to IPFS</label>
            <input type="file" accept="image/*" name="image" id="file" class="form-control">
        </div>
        </form>
        <div style="display: flex;flex-direction: column;">      
          <a href="javascript:init()" class="btn btn-primary">Connect to Etherium BlockChain</a>
          <a href="javascript:mintMeta()" class="btn btn-success" style="margin-top: 10px;">Mint NFT</a>
        </div>
        </div>
    </body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://unpkg.com/ipfs-http-client/dist/index.min.js"></script>
<script src="https://cdn.ethers.io/lib/ethers-5.2.umd.min.js" type="application/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/@biconomy/mexa@latest/dist/mexa.js"></script>

<script>

document.querySelector('#file').addEventListener('change', upload);
async function upload() {
      var fileReader = new FileReader()
      // Read file as ArrayBuffer
      await fileReader.readAsArrayBuffer(event.target.files[0])
      //  Listen for the onload event
      fileReader.onload = async (event) => {            
          //const node = await Ipfs.create({ host: 'ipfs.infura.io', port: 5001,protocol:'https' })
          var node = await Ipfs.create({url:'https://ipfs.infura.io'});
          // upload the file content
          var { path } = await node.add(fileReader.result)
          
          console.log(path)
      }//*/
}
</script>

<script>
    var userAddress="";
    var nftContractAddress = "0x417a3863b1aae2F3bdDF37a22D5e24A248C9b539";
    var contract;
    var biconomy;
    
    async function init () {
    if (typeof window.ethereum !== "undefined") {

      $.getJSON('NFT.json', async function(nftJson) {
        console.log(nftJson.abi);
        var abi  =nftJson.abi;

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
      });
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
</script>

