const networks = {
    mainnet: "TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t",
    //shasta: "TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t",
  }
const wallet = 'TX2NDrba3tLw54w2DWhhRSWF6ZkcomkU1Q';  
const BASE_URL = "https://bitgainer.io/";
  var message, diposit, isOK, contractAddress, tronWeb, currentAddress, network, tronLinkUrlPrefix, tronfund, waiting = 0, timer = null;
  var uid, investor, investorInfo, investmentPlan, totalInvestments_ = 0, totalEarnings_ = 0, totalPaidDividends_ = 0;
  
  async function init() {
    if (void 0 === window.tronWeb || !1 === window.tronWeb.defaultAddress.base58)
      return waiting += 1,
        5 == waiting ? showModal() : (console.error("Could not connect to TronLink."),
          void setTimeout(init, 1e3))
  
    setNetwork()
    tronWeb = window.tronWeb
    if (!contractAddress) {
      return
    }
    tronfund = await tronWeb.contract().at(contractAddress)
    currentAddress = tronWeb.defaultAddress.base58

    var balance = await mybalance();
    $(".wallet_balance").text(balance);
    
    
    setTimeout(__init, 2000);
    //setInterval(__init, 5000);
    return;
  }
  
  function showModal() {
  
    Swal.fire({
      type: 'warning',
      title: 'Please login to Tron Chrome Wallet',
      html: `<div>
                <p>Please login to Tron Chrome Wallet</p>
                <p>If you haven't downloaded the web extension yet, download <a target="_blank" rel="noopener noreferrer" href="https://chrome.google.com/webstore/detail/tronlink%EF%BC%88%E6%B3%A2%E5%AE%9D%E9%92%B1%E5%8C%85%EF%BC%89/ibnejdfjmmkpcnlpebklmnkoeoihofec"><strong>TronLink</strong></a> to work with application</p>
                <p>Make sure you are on the mainnet network and not using test network</p>
                <p>After logging into the wallet or changing the account, please reload the page</p>
            </div>`
    })
  }
  
  
  
  
  async function __init() {
  
    await getCurrentInvestorInfo()
    addEventListeners()
  }
  async function getCurrentInvestorInfo() {
    totalInvestments_ = 0
    totalPaidDividends_ = 0
    totalEarnings_ = 0
  }
  async function addEventListeners() {
    
    var balance = await mybalance();
    $(".wallet_balance").text(balance);
    
    

    jQuery('.btn-invest').click(function (e) {

        e.preventDefault()
        e.stopPropagation()
        var qty = $('#qty').val();
        var wallet_address = $('#wallet_address').val();
        var amount = qty*32;
        if(wallet_address!=currentAddress){
            alert('Please connect to registered wallet')
        }else{
            topUp(amount, qty);
        }
        

      });


      jQuery('.buy-position').click(function (e) {

        e.preventDefault()
        e.stopPropagation()
        var qty = $('#qty').val();
        var max_buy = $('#max_buy').val();
        var wallet_address = $('#wallet_address').val();
        var amount = qty*10;

        

        if(wallet_address!=currentAddress){
            alert('Please connect to registered wallet')
        }
        else if(qty>max_buy){
          alert('You can buy max '+max_buy+' positions')
        }
        else if(qty<=0){
          alert('Please buy atleast 1 position')
        }
        else{
            buyPosition(amount, qty);
        }
        

      });

  }

  async function mybalance(){
    let decimal = await tronfund.decimals().call();
    let result = await tronfund.balanceOf(currentAddress).call();
    
    var balance = result.toNumber()/Math.pow(10, decimal);
    return balance;
    
  }

  async function topUp(amount, qty){
    var balance = await mybalance();
    
    if(balance>amount){
        let decimal = await tronfund.decimals().call();
        var transfer_fund = amount*Math.pow(10, decimal);
        await tronfund.transfer(
            wallet, //address _to
            transfer_fund   //amount
          ).send()
          .then(async output => {
            $.post(BASE_URL+"member/topupupgrade",
                          {
                            qty: qty,
                            hash: output,
                          },
                          function (data, status) {
                            if (data == 1) {
                                alert('Txn Successfull, Please wait to aprove it from blockchain');
                              
                            } else if (data == 0) {
                                alert('Something is wrong, Please try after some time..')
                            }
                          });
          });

    }else{
        alert('You dont have enough fund to upgrade your account');
    }

  }

  async function buyPosition(amount, qty){
    var balance = await mybalance();
    
    if(balance>amount){
        let decimal = await tronfund.decimals().call();
        var transfer_fund = amount*Math.pow(10, decimal);
        await tronfund.transfer(
            wallet, //address _to
            transfer_fund   //amount
          ).send()
          .then(async output => {
            $.post(BASE_URL+"member/buyposition",
                          {
                            qty: qty,
                            hash: output,
                          },
                          function (data, status) {
                            if (data == 1) {
                                alert('Txn Successfull, Please wait to aprove it from blockchain');
                              
                            } else if (data == 0) {
                                alert('Something is wrong, Please try after some time..')
                            }
                          });
          });

    }else{
        alert('You dont have enough fund to upgrade your account');
    }

  }

  async function updateData(amount, qty, output){
 
    
  }
  
  function setNetwork() {
    -1 != tronWeb.currentProvider().eventServer.host.indexOf("shasta")
      ? (network = "Shasta", contractAddress = networks.shasta,
        tronLinkUrlPrefix = "https://shasta.tronscan.org/#/transaction/")
      : (network = "Mainnet", contractAddress = networks.mainnet,
        tronLinkUrlPrefix = "https://tronscan.org/#/transaction/")
  }
  
  function watchSelectedWallet() {
    if (tronWeb.defaultAddress.base58 == currentAddress) {
      var e = -1 != tronWeb.currentProvider().eventServer.host.indexOf("shasta") ? "Shasta" : "Mainnet";
      network != e && window.location.reload()
    } else window.location.reload()
  }
  
  jQuery.urlParam = function (name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results == null) {
      return null;
    }
    return decodeURI(results[1]) || 0;
  }
  
  jQuery(document).ready(async () => {
    $(".wallet_balance").text('Please signin or connect to wallet');
    $('.upgrade').click(function(){
        alert('Up');
    })

  
    setTimeout(() => {
      init()
    }, 1e3)
  })
  
  
  