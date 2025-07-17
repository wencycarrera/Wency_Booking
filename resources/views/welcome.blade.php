<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Booking System</title>

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <style>
    /* Background overlay */
    .bg-overlay {
      background-color: rgba(0,0,0,0.55);
      position: absolute;
      inset: 0;
      z-index: 0;
      backdrop-filter: blur(3px);
    }

    /* Fade-in animation */
    @keyframes fadeInUp {
      0% {
        opacity: 0;
        transform: translateY(15px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .fade-in-up {
      animation: fadeInUp 0.7s ease forwards;
    }
  </style>
</head>
<body class="relative min-h-screen font-sans bg-gray-100">

  <!-- Hero Section -->
  <section class="relative min-h-screen bg-cover bg-center flex justify-center items-center px-6"
           style="background-image: url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1470&q=80');">
    <div class="bg-overlay"></div>

    <div class="relative z-10 w-full max-w-5xl rounded-3xl p-12 shadow-2xl bg-white/10 backdrop-blur-sm fade-in-up flex flex-col md:flex-row items-center gap-10">
      
      <!-- Left: Welcome Text -->
      <div class="flex-1 text-white">
        <h2 class="text-4xl font-extrabold mb-4 tracking-tight">
          Welcome to <span class="text-pink-400">My Booking System</span>
        </h2>
        <p class="text-lg leading-relaxed max-w-md">
          Manage your appointments easily and efficiently. Seamless booking at your fingertips.
        </p>
      </div>

      <!-- Right: Buttons -->
      <div class="flex flex-col space-y-6 w-full max-w-xs">
        <a href="{{ route('register') }}" 
           class="block w-full px-8 py-4 bg-pink-500 text-white font-semibold rounded-xl shadow-lg hover:bg-pink-600 hover:shadow-xl transform hover:scale-105 transition text-center">
          Register
        </a>

        <a href="{{ route('login') }}" 
           class="block w-full px-8 py-4 border-2 border-pink-500 text-pink-500 font-semibold rounded-xl shadow hover:bg-pink-50 hover:scale-105 transform transition text-center">
          Login
        </a>
      </div>

    </div>
  </section>

</body>
</html>
