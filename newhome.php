<html>
  <head>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
    <link
      rel="stylesheet"
      as="style"
      onload="this.rel='stylesheet'"
      href="https://fonts.googleapis.com/css2?display=swap&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900&amp;family=Space+Grotesk%3Awght%40400%3B500%3B700"
    />

    <title>Galileo Design</title>
    <link rel="icon" type="image/x-icon" href="data:image/x-icon;base64," />

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  </head>
  <body>
    <div class="relative flex size-full min-h-screen flex-col bg-white group/design-root overflow-x-hidden" style='font-family: "Space Grotesk", "Noto Sans", sans-serif;'>
      <div class="layout-container flex h-full grow flex-col">
        <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#f0f2f5] px-10 py-3">
          <div class="flex items-center gap-4 text-[#111518]">
            <div class="size-4">
              <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_6_535)">
                  <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M47.2426 24L24 47.2426L0.757355 24L24 0.757355L47.2426 24ZM12.2426 21H35.7574L24 9.24264L12.2426 21Z"
                    fill="currentColor"
                  ></path>
                </g>
                <defs>
                  <clipPath id="clip0_6_535"><rect width="48" height="48" fill="white"></rect></clipPath>
                </defs>
              </svg>
            </div>
            <h2 class="text-[#111518] text-lg font-bold leading-tight tracking-[-0.015em]">Bioinformatics.AI</h2>
          </div>
          <div class="flex flex-1 justify-end gap-8">
            <div class="flex items-center gap-9">
              <a class="text-[#111518] text-sm font-medium leading-normal" href="#">Features</a>
              <a class="text-[#111518] text-sm font-medium leading-normal" href="#">Pricing</a>
              <a class="text-[#111518] text-sm font-medium leading-normal" href="#">Resources</a>
              <a class="text-[#111518] text-sm font-medium leading-normal" href="#">Company</a>
            </div>
            <button
              class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-10 px-4 bg-[#2094f3] text-white text-sm font-bold leading-normal tracking-[0.015em]"
            >
              <span class="truncate">New</span>
            </button>
            <div
              class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10"
              style='background-image: url("https://cdn.usegalileo.ai/sdxl10/314d6cd1-8e3a-4337-b3ae-da8929f9f149.png");'
            ></div>
          </div>
        </header>
        <div class="px-40 flex flex-1 justify-center py-5">
          <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
            <div class="@container">
              <div class="@[480px]:p-4">
                <div
                  class="flex min-h-[480px] flex-col gap-6 bg-cover bg-center bg-no-repeat @[480px]:gap-8 @[480px]:rounded-xl items-start justify-end px-4 pb-10 @[480px]:px-10"
                  style='background-image: linear-gradient(rgba(0, 0, 0, 0.1) 0%, rgba(0, 0, 0, 0.4) 100%), url("https://cdn.usegalileo.ai/sdxl10/312443da-4647-43ed-b912-202ec21048d6.png");'
                >
                  <div class="flex flex-col gap-2 text-left">
                    <h1
                      class="text-white text-4xl font-black leading-tight tracking-[-0.033em] @[480px]:text-5xl @[480px]:font-black @[480px]:leading-tight @[480px]:tracking-[-0.033em]"
                    >
                      Introducing Multiomics Explorer
                    </h1>
                    <h2 class="text-white text-sm font-normal leading-normal @[480px]:text-base @[480px]:font-normal @[480px]:leading-normal">
                      A powerful new way to combine and analyze RNA, ChIP, ATAC, and more. Learn more in our blog post.
                    </h2>
                  </div>
                  <button
                    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-10 px-4 @[480px]:h-12 @[480px]:px-5 bg-[#2094f3] text-white text-sm font-bold leading-normal tracking-[0.015em] @[480px]:text-base @[480px]:font-bold @[480px]:leading-normal @[480px]:tracking-[0.015em]"
                  >
                    <span class="truncate">Read the blog</span>
                  </button>
                </div>
              </div>
            </div>
            <div class="flex flex-col gap-10 px-4 py-10 @container">
              <div class="flex flex-col gap-6">
                <div class="flex flex-col gap-4">
                  <h1
                    class="text-[#111518] tracking-light text-[32px] font-bold leading-tight @[480px]:text-4xl @[480px]:font-black @[480px]:leading-tight @[480px]:tracking-[-0.033em] max-w-[720px]"
                  >
                    Key Features
                  </h1>
                  <p class="text-[#111518] text-base font-normal leading-normal max-w-[720px]">
                    Our new Multiomics Explorer integrates RNA-seq, ChIP-seq, ATAC-seq, and other data types, making it easy to combine and analyze multiomics data.
                  </p>
                </div>
                <button
                  class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-10 px-4 @[480px]:h-12 @[480px]:px-5 bg-[#2094f3] text-white text-sm font-bold leading-normal tracking-[0.015em] @[480px]:text-base @[480px]:font-bold @[480px]:leading-normal @[480px]:tracking-[0.015em] w-fit"
                >
                  <span class="truncate">Learn more</span>
                </button>
              </div>
              <div class="grid grid-cols-[repeat(auto-fit,minmax(158px,1fr))] gap-3">
                <div class="flex flex-col gap-3 pb-3">
                  <div
                    class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-xl"
                    style='background-image: url("https://cdn.usegalileo.ai/sdxl10/ad8573df-adcb-4005-b7b0-43615cd732fa.png");'
                  ></div>
                  <div>
                    <p class="text-[#111518] text-base font-medium leading-normal">Genome Browser</p>
                    <p class="text-[#60778a] text-sm font-normal leading-normal">Visualize your data on the human genome</p>
                  </div>
                </div>
                <div class="flex flex-col gap-3 pb-3">
                  <div
                    class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-xl"
                    style='background-image: url("https://cdn.usegalileo.ai/sdxl10/6add78ad-f006-46a4-bd37-c2fa2874dd91.png");'
                  ></div>
                  <div>
                    <p class="text-[#111518] text-base font-medium leading-normal">RNA-seq Heatmap</p>
                    <p class="text-[#60778a] text-sm font-normal leading-normal">See gene expression patterns across samples</p>
                  </div>
                </div>
                <div class="flex flex-col gap-3 pb-3">
                  <div
                    class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-xl"
                    style='background-image: url("https://cdn.usegalileo.ai/sdxl10/ec6ef0d5-45ff-4170-837d-14056472038a.png");'
                  ></div>
                  <div>
                    <p class="text-[#111518] text-base font-medium leading-normal">ChIP-seq Peaks</p>
                    <p class="text-[#60778a] text-sm font-normal leading-normal">Detect transcription factor binding sites</p>
                  </div>
                </div>
                <div class="flex flex-col gap-3 pb-3">
                  <div
                    class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-xl"
                    style='background-image: url("https://cdn.usegalileo.ai/sdxl10/53cc1ecf-2f52-4642-be9d-d85f8f76a584.png");'
                  ></div>
                  <div>
                    <p class="text-[#111518] text-base font-medium leading-normal">GO Enrichment</p>
                    <p class="text-[#60778a] text-sm font-normal leading-normal">Find biological functions enriched in your data</p>
                  </div>
                </div>
              </div>
            </div>
            <h2 class="text-[#111518] text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Explore Multiomics Data with Galaxy</h2>
            <div class="grid grid-cols-[repeat(auto-fit,minmax(158px,1fr))] gap-3 p-4">
              <div class="flex flex-col gap-3 pb-3">
                <div
                  class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-xl"
                  style='background-image: url("https://cdn.usegalileo.ai/sdxl10/1513fbb5-d813-49e8-ac6a-9b870596502b.png");'
                ></div>
                <div>
                  <p class="text-[#111518] text-base font-medium leading-normal">Pathway Enrichment</p>
                  <p class="text-[#60778a] text-sm font-normal leading-normal">Identify pathways enriched in your genes</p>
                </div>
              </div>
              <div class="flex flex-col gap-3 pb-3">
                <div
                  class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-xl"
                  style='background-image: url("https://cdn.usegalileo.ai/sdxl10/df874db9-4700-4c22-ac2e-5320c01293cb.png");'
                ></div>
                <div>
                  <p class="text-[#111518] text-base font-medium leading-normal">Network Visualization</p>
                  <p class="text-[#60778a] text-sm font-normal leading-normal">View interactions between genes and proteins</p>
                </div>
              </div>
              <div class="flex flex-col gap-3 pb-3">
                <div
                  class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-xl"
                  style='background-image: url("https://cdn.usegalileo.ai/sdxl10/e59232b6-0fc3-458e-8c18-fcab2ec281f4.png");'
                ></div>
                <div>
                  <p class="text-[#111518] text-base font-medium leading-normal">Multiomics Integration</p>
                  <p class="text-[#60778a] text-sm font-normal leading-normal">Combine and analyze RNA, ChIP, ATAC, and more</p>
                </div>
              </div>
            </div>
            <div class="@container">
              <div class="flex flex-col gap-6 px-4 py-10 @[480px]:gap-8 @[864px]:flex-row">
                <div
                  class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-xl @[480px]:h-auto @[480px]:min-w-[400px] @[864px]:w-full"
                  style='background-image: url("https://cdn.usegalileo.ai/sdxl10/41eb9b51-186d-4d20-887a-aca4dd449351.png");'
                ></div>
                <div class="flex flex-col gap-6 @[480px]:min-w-[400px] @[480px]:gap-8 @[864px]:justify-center">
                  <div class="flex flex-col gap-2 text-left">
                    <h1
                      class="text-[#111518] text-4xl font-black leading-tight tracking-[-0.033em] @[480px]:text-5xl @[480px]:font-black @[480px]:leading-tight @[480px]:tracking-[-0.033em]"
                    >
                      Learn from Experts
                    </h1>
                    <h2 class="text-[#111518] text-sm font-normal leading-normal @[480px]:text-base @[480px]:font-normal @[480px]:leading-normal">
                      Join our webinar series to hear from top researchers and industry experts about the latest methods and applications in genomics.
                    </h2>
                  </div>
                  <button
                    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-10 px-4 @[480px]:h-12 @[480px]:px-5 bg-[#2094f3] text-white text-sm font-bold leading-normal tracking-[0.015em] @[480px]:text-base @[480px]:font-bold @[480px]:leading-normal @[480px]:tracking-[0.015em]"
                  >
                    <span class="truncate">Register Now</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <footer class="flex justify-center">
          <div class="flex max-w-[960px] flex-1 flex-col">
            <footer class="flex flex-col gap-6 px-5 py-10 text-center @container">
              <div class="flex flex-wrap items-center justify-center gap-6 @[480px]:flex-row @[480px]:justify-around">
                <a class="text-[#60778a] text-base font-normal leading-normal min-w-40" href="#">Features</a>
                <a class="text-[#60778a] text-base font-normal leading-normal min-w-40" href="#">Pricing</a>
                <a class="text-[#60778a] text-base font-normal leading-normal min-w-40" href="#">Resources</a>
                <a class="text-[#60778a] text-base font-normal leading-normal min-w-40" href="#">Company</a>
                <a class="text-[#60778a] text-base font-normal leading-normal min-w-40" href="#">Help Center</a>
              </div>
              <div class="flex flex-wrap justify-center gap-4">
                <a href="#">
                  <div class="text-[#60778a]" data-icon="TwitterLogo" data-size="24px" data-weight="regular">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                      <path
                        d="M247.39,68.94A8,8,0,0,0,240,64H209.57A48.66,48.66,0,0,0,168.1,40a46.91,46.91,0,0,0-33.75,13.7A47.9,47.9,0,0,0,120,88v6.09C79.74,83.47,46.81,50.72,46.46,50.37a8,8,0,0,0-13.65,4.92c-4.31,47.79,9.57,79.77,22,98.18a110.93,110.93,0,0,0,21.88,24.2c-15.23,17.53-39.21,26.74-39.47,26.84a8,8,0,0,0-3.85,11.93c.75,1.12,3.75,5.05,11.08,8.72C53.51,229.7,65.48,232,80,232c70.67,0,129.72-54.42,135.75-124.44l29.91-29.9A8,8,0,0,0,247.39,68.94Zm-45,29.41a8,8,0,0,0-2.32,5.14C196,166.58,143.28,216,80,216c-10.56,0-18-1.4-23.22-3.08,11.51-6.25,27.56-17,37.88-32.48A8,8,0,0,0,92,169.08c-.47-.27-43.91-26.34-44-96,16,13,45.25,33.17,78.67,38.79A8,8,0,0,0,136,104V88a32,32,0,0,1,9.6-22.92A30.94,30.94,0,0,1,167.9,56c12.66.16,24.49,7.88,29.44,19.21A8,8,0,0,0,204.67,80h16Z"
                      ></path>
                    </svg>
                  </div>
                </a>
                <a href="#">
                  <div class="text-[#60778a]" data-icon="FacebookLogo" data-size="24px" data-weight="regular">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                      <path
                        d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm8,191.63V152h24a8,8,0,0,0,0-16H136V112a16,16,0,0,1,16-16h16a8,8,0,0,0,0-16H152a32,32,0,0,0-32,32v24H96a8,8,0,0,0,0,16h24v63.63a88,88,0,1,1,16,0Z"
                      ></path>
                    </svg>
                  </div>
                </a>
                <a href="#">
                  <div class="text-[#60778a]" data-icon="LinkedinLogo" data-size="24px" data-weight="regular">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                      <path
                        d="M216,24H40A16,16,0,0,0,24,40V216a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V40A16,16,0,0,0,216,24Zm0,192H40V40H216V216ZM96,112v64a8,8,0,0,1-16,0V112a8,8,0,0,1,16,0Zm88,28v36a8,8,0,0,1-16,0V140a20,20,0,0,0-40,0v36a8,8,0,0,1-16,0V112a8,8,0,0,1,15.79-1.78A36,36,0,0,1,184,140ZM100,84A12,12,0,1,1,88,72,12,12,0,0,1,100,84Z"
                      ></path>
                    </svg>
                  </div>
                </a>
              </div>
              <p class="text-[#60778a] text-base font-normal leading-normal">@2022 Bioinformatics.AI</p>
            </footer>
          </div>
        </footer>
      </div>
    </div>
  </body>
</html>
