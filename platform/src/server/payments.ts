/**
 * Payment adapter interface.
 * v1 ships a `dev` adapter that simulates license payment and DIRECT_DEPOSIT confirmation.
 * Stripe / crypto adapters can implement the same contract without touching compensation.
 */
export type LicenseInvoice = {
  id: string;
  userId: string;
  tier: string;
  amountCents: bigint;
  status: "simulated" | "pending" | "paid";
};

export interface PaymentAdapter {
  name: string;
  payLicense(userId: string, tier: string, amountCents: bigint): Promise<LicenseInvoice>;
  simulateDirectDeposit(opts: {
    userId: string;
    amountCents: bigint;
    memo: string;
  }): Promise<{ confirmed: boolean; txHash: string }>;
}

export class DevPaymentAdapter implements PaymentAdapter {
  name = "dev";
  async payLicense(userId: string, tier: string, amountCents: bigint): Promise<LicenseInvoice> {
    return { id: `dev-lic-${userId}-${tier}`, userId, tier, amountCents, status: "simulated" };
  }
  async simulateDirectDeposit(opts: { userId: string; amountCents: bigint; memo: string }) {
    return { confirmed: true, txHash: `dev-${opts.memo}` };
  }
}

export function getPaymentAdapter(): PaymentAdapter {
  return new DevPaymentAdapter();
}
